@csrf

<div class="grid grid-cols-1 gap-6">
    <div>
        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
            Nama Paket
        </label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $package->name ?? '') }}"
            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            placeholder="Contoh: Premium"
            required
        >
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="speed_mbps" class="mb-2 block text-sm font-semibold text-slate-700">
            Kecepatan (Mbps)
        </label>
        <input
            type="number"
            id="speed_mbps"
            name="speed_mbps"
            value="{{ old('speed_mbps', $package->speed_mbps ?? '') }}"
            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            placeholder="Contoh: 50"
            min="1"
        >
        @error('speed_mbps')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="price" class="mb-2 block text-sm font-semibold text-slate-700">
            Harga
        </label>
        <input
            type="number"
            id="price"
            name="price"
            value="{{ old('price', $package->price ?? '') }}"
            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            placeholder="Contoh: 350000"
            min="0"
            required
        >
        @error('price')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">
            Deskripsi
        </label>
        <textarea
            id="description"
            name="description"
            rows="4"
            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            placeholder="Deskripsi paket (opsional)"
        >{{ old('description', $package->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="flex items-center gap-3 text-sm font-semibold text-slate-700">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                class="rounded border-slate-300"
                {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }}
            >
            <span>Paket aktif</span>
        </label>
    </div>
</div>

<div class="mt-8 flex items-center gap-3">
    <button type="submit"
            class="rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-600">
        Simpan
    </button>

    <a href="{{ route('admin.packages.index') }}"
       class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
        Batal
    </a>
</div>