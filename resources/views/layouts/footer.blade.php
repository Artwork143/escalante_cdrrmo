<footer id="pageFooter" class="bg-[#EB8317] text-white py-4 {{request()->routeIs('admin') ? 'hidden' : '' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="container items-center flex justify-between">
        <!-- Footer Left Section (All Rights Reserved) -->
        <div>
            <span>© {{ date('Y') }} Escalante CDRRMO. All rights reserved.</span>
        </div>
        
        <!-- Footer Right Section (Facebook Link) -->
        <div>
            <a href="https://www.facebook.com/EscalanteCitydrrmo" target="_blank" class="text-white hover:text-blue-500">
                <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M22.675 0h-21.35C.598 0 0 .6 0 1.337v21.326C0 23.4.598 24 1.325 24H12.81v-9.293H9.692V10.5h3.117V8.004c0-3.096 1.892-4.785 4.656-4.785 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.312h3.59l-.468 3.207h-3.122V24h6.116C23.402 24 24 23.4 24 22.663V1.337C24 .6 23.402 0 22.675 0z"/>
                </svg>
            </a>
        </div>
    </div>
    </div>
</footer>
