<div
    x-data="{
        pad: null,
        saved: false,
        init() {
            this.pad = new SignaturePad(this.$refs.canvas, { backgroundColor: 'rgb(255,255,255)' });
            this.resize();
            window.addEventListener('resize', () => this.resize());
            // The pad can start out inside a hidden (x-show) tab panel, where
            // offsetWidth/Height are 0. Re-measure once its box actually gets size.
            new ResizeObserver(() => this.resize()).observe(this.$refs.canvas);
        },
        resize() {
            const canvas = this.$refs.canvas;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const data = this.pad && !this.pad.isEmpty() ? this.pad.toData() : null;
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            this.pad.clear();
            if (data) { this.pad.fromData(data); }
        },
        clearPad() {
            this.pad.clear();
            this.saved = false;
        },
        async saveSignature() {
            if (this.pad.isEmpty()) { return; }
            const dataUrl = this.pad.toDataURL('image/png');
            await $wire.save(dataUrl);
            this.saved = true;
        },
    }"
    x-init="init()"
    wire:ignore.self
    class="space-y-2"
>
    @if ($existingUrl)
        <div class="flex items-center gap-3 rounded-md border border-slate-200 bg-slate-50 p-3">
            <img src="{{ $existingUrl }}" alt="Saved signature" class="h-16 rounded bg-white border border-slate-200">
            <div class="text-sm">
                <p class="font-medium text-slate-700">Signature on file</p>
                @unless ($readOnly)
                <button type="button" wire:click="removeSignature" wire:confirm="Remove the saved signature and draw a new one?" class="text-xs text-red-600 hover:underline">Remove &amp; redraw</button>
                @endunless
            </div>
        </div>
    @endif

    @unless ($readOnly)
        <div class="rounded-md border border-slate-300 bg-white">
            <canvas x-ref="canvas" class="h-32 w-full touch-none rounded-md" style="touch-action:none"></canvas>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" @click="clearPad()" class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200">Clear</button>
            <button type="button" @click="saveSignature()" class="rounded-md bg-brand-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-600">Save signature</button>
            <span x-show="saved" x-transition x-cloak class="text-xs font-medium text-green-600">✓ Saved</span>
        </div>
        <p class="text-xs text-slate-400">Draw with your mouse, finger or stylus.</p>
    @endunless
</div>
