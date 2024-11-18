<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-4xl mx-auto p-4">
                        <!-- Timeline container -->
                        <div class="relative">
                            <!-- Vertical line -->
                            <div class="absolute left-6 top-0 h-full w-0.5 bg-gray-200"></div>

                            <!-- Timeline Items -->
                            <div class="relative flex items-center mb-8">
                                <!-- Marker -->
                                <div
                                    class="w-12 h-12 flex items-center justify-center bg-green-500 text-white rounded-full z-10">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <!-- Content -->
                                <div class="ml-8">
                                    <h4 class="text-lg font-bold">Order Placed</h4>
                                    <p class="text-gray-600 text-sm">Your order was successfully placed.</p>
                                    <span class="text-xs text-gray-500">11:00 AM</span>
                                </div>
                            </div>

                            <div class="relative flex items-center mb-8">
                                <!-- Marker -->
                                <div
                                    class="w-12 h-12 flex items-center justify-center bg-yellow-500 text-white rounded-full z-10">
                                    <i class="fa-solid fa-bowl-food"></i>
                                </div>
                                <!-- Content -->
                                <div class="ml-8">
                                    <h4 class="text-lg font-bold">Preparing</h4>
                                    <p class="text-gray-600 text-sm">The restaurant is preparing your order.</p>
                                    <span class="text-xs text-gray-500">11:15 AM</span>
                                </div>
                            </div>

                            <div class="relative flex items-center mb-8">
                                <!-- Marker -->
                                <div
                                    class="w-12 h-12 flex items-center justify-center bg-blue-500 text-white rounded-full z-10">
                                    <i class="fa-solid fa-truck"></i>
                                </div>
                                <!-- Content -->
                                <div class="ml-8">
                                    <h4 class="text-lg font-bold">Out for Delivery</h4>
                                    <p class="text-gray-600 text-sm">Your order is on the way!</p>
                                    <span class="text-xs text-gray-500">11:30 AM</span>
                                </div>
                            </div>

                            <div class="relative flex items-center">
                                <!-- Marker -->
                                <div
                                    class="w-12 h-12 flex items-center justify-center bg-green-500 text-white rounded-full z-10">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <!-- Content -->
                                <div class="ml-8">
                                    <h4 class="text-lg font-bold">Delivered</h4>
                                    <p class="text-gray-600 text-sm">Your order has been delivered!</p>
                                    <span class="text-xs text-gray-500">11:45 AM</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>