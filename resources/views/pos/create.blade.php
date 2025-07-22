<x-app-layout>
    <x-slot name="header">
        {{ __('Point of Sale (POS)') }}
    </x-slot>

    <form id="pos-form" action="{{ route('pos.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Input Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Detail Pelanggan & Jasa -->
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="font-semibold text-lg text-brand-dark-stone mb-4">Detail Transaksi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium">Nama Pelanggan</label>
                            <input type="text" name="customer_name" id="customer_name" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('customer_name', 'Walk-in Customer') }}">
                        </div>
                        <div>
                            <label for="package_id" class="block text-sm font-medium">Paket Jasa </label>
                            <select name="package_id" id="package_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="">-- Tanpa Paket Jasa --</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" data-price="{{ $package->price }}">
                                        {{-- Tambahkan durasi di sini --}}
                                        {{ $package->name }} @if($package->duration_minutes) ({{ $package->duration_minutes }} Menit) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="therapist-selection" class="hidden">
                            <label for="therapist_user_id" class="block text-sm font-medium">Pilih Terapis</label>
                            <select name="therapist_user_id" id="therapist_user_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="">-- Pilih Terapis --</option>
                                @foreach($therapists as $therapist)
                                    <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Penjualan Produk -->
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="font-semibold text-lg text-brand-dark-stone mb-4">Tambah Produk</h3>
                    <div class="flex items-end space-x-2">
                        <div class="flex-grow">
                            <label for="product-search" class="block text-sm font-medium">Pilih Produk</label>
                            <select id="product-search" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="">-- Pilih Produk untuk Ditambahkan --</option>
                                @foreach($availableProducts as $product)
                                    <option value="{{ $product->id }}" 
                                            data-name="{{ $product->name }}" 
                                            data-price="{{ $product->pivot->selling_price }}"
                                            data-stock="{{ $product->pivot->stock_quantity }}">
                                        {{ $product->name }} (Stok: {{ $product->pivot->stock_quantity }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" id="add-product-btn" class="px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg">Tambah</button>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Ringkasan & Total -->
            <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-sm space-y-4">
                <h3 class="font-semibold text-lg text-brand-dark-stone">Keranjang Belanja</h3>
                <!-- Container untuk item -->
                <div id="cart-items" class="space-y-3 max-h-64 overflow-y-auto">
                    <!-- Item akan ditambahkan oleh JavaScript -->
                </div>
                <!-- Total -->
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-lg">
                        <span>Total</span>
                        <span id="total-price" class="font-bold">Rp 0</span>
                    </div>
                </div>
                <button type="submit" class="w-full mt-4 px-6 py-3 bg-brand-deep-teal text-white font-bold rounded-lg text-lg">
                    BUAT TRANSAKSI
                </button>
            </div>
        </div>
    </form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cart = {
        package: null,
        products: {}
    };

    const packageSelect = document.getElementById('package_id');
    const therapistSelection = document.getElementById('therapist-selection');
    const productSearch = document.getElementById('product-search');
    const addProductBtn = document.getElementById('add-product-btn');
    const cartItemsContainer = document.getElementById('cart-items');
    const totalPriceEl = document.getElementById('total-price');
    const posForm = document.getElementById('pos-form');

    function renderCart() {
        cartItemsContainer.innerHTML = '';
        let total = 0;

        // Render paket
        if (cart.package) {
            const itemEl = document.createElement('div');
            itemEl.className = 'flex justify-between items-center text-sm';
            itemEl.innerHTML = `
                <div>
                    <p class="font-medium">${cart.package.name}</p>
                    <p class="text-xs text-gray-500">Jasa</p>
                </div>
                <div class="font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(cart.package.price)}</div>
            `;
            cartItemsContainer.appendChild(itemEl);
            total += cart.package.price;
        }

        // Render produk
        for (const id in cart.products) {
            const item = cart.products[id];
            const itemEl = document.createElement('div');
            itemEl.className = 'flex justify-between items-center text-sm';
            itemEl.innerHTML = `
                <div>
                    <p class="font-medium">${item.name}</p>
                    <div class="flex items-center space-x-2 mt-1">
                        <input type="number" value="${item.quantity}" min="1" max="${item.stock}" data-id="${id}" class="quantity-input w-16 text-center border-gray-300 rounded-md shadow-sm">
                        <button type="button" data-id="${id}" class="remove-product-btn text-red-500 hover:text-red-700">&times;</button>
                    </div>
                </div>
                <div class="font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(item.price * item.quantity)}</div>
            `;
            cartItemsContainer.appendChild(itemEl);
            total += item.price * item.quantity;
        }
        
        totalPriceEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
    }

    function updateCartInputs() {
        // Hapus input produk lama
        posForm.querySelectorAll('input[name^="products["]').forEach(el => el.remove());
        
        // Buat input baru berdasarkan keranjang
        Object.keys(cart.products).forEach((id, index) => {
            const item = cart.products[id];
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = `products[${index}][id]`;
            idInput.value = id;
            posForm.appendChild(idInput);

            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = `products[${index}][quantity]`;
            qtyInput.value = item.quantity;
            posForm.appendChild(qtyInput);
        });
    }

    packageSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            cart.package = {
                id: selectedOption.value,
                name: selectedOption.text,
                price: parseFloat(selectedOption.dataset.price)
            };
            therapistSelection.classList.remove('hidden');
            document.getElementById('therapist_user_id').required = true;
        } else {
            cart.package = null;
            therapistSelection.classList.add('hidden');
            document.getElementById('therapist_user_id').required = false;
        }
        renderCart();
    });

    addProductBtn.addEventListener('click', function() {
        const selectedOption = productSearch.options[productSearch.selectedIndex];
        if (!selectedOption.value) return;

        const productId = selectedOption.value;

        if (cart.products[productId]) {
            // Jika sudah ada, tambahkan quantity
            if (cart.products[productId].quantity < cart.products[productId].stock) {
                 cart.products[productId].quantity++;
            }
        } else {
            // Jika belum ada, tambahkan ke keranjang
            cart.products[productId] = {
                name: selectedOption.dataset.name,
                price: parseFloat(selectedOption.dataset.price),
                stock: parseInt(selectedOption.dataset.stock, 10),
                quantity: 1
            };
        }
        productSearch.value = ''; // Reset dropdown
        renderCart();
    });

    cartItemsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product-btn')) {
            const productId = e.target.dataset.id;
            delete cart.products[productId];
            renderCart();
        }
    });
    
    cartItemsContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            const productId = e.target.dataset.id;
            let newQuantity = parseInt(e.target.value, 10);
            if (newQuantity > cart.products[productId].stock) {
                newQuantity = cart.products[productId].stock;
                e.target.value = newQuantity;
            }
            if (newQuantity < 1) {
                newQuantity = 1;
                e.target.value = newQuantity;
            }
            cart.products[productId].quantity = newQuantity;
            renderCart();
        }
    });

    posForm.addEventListener('submit', function(e) {
        updateCartInputs();
    });
});
</script>
</x-app-layout>
