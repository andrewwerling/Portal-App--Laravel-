<div class="flex items-center">
     <!-- Primary logo - always visible -->
     <img src="https://festivalwifiguys.com/wp-content/uploads/2023/03/Artboard-5.png" 
          alt="Festival Wifi Guys Logo"
          class="h-20 lg:h-34 lg:max-w-[360px] object-contain border-2 border-white rounded-full"
     />

     <!-- Secondary logo - hidden on mobile for welcome page, visible otherwise -->
     <div x-data="{ show: !['/', '/welcome'].includes(window.location.pathname) }" 
          :class="{ 'hidden md:block': !show }">
          <img x-data="{ isDark: window.matchMedia('(prefers-color-scheme: dark)').matches }" 
               x-init="$watch('isDark', () => {})"
               :src="isDark ? 'https://festivalwifiguys.com/wp-content/uploads/2025/04/back-white-for-website.png' : 'https://festivalwifiguys.com/wp-content/uploads/2025/04/back-for-website.png'"
               :alt="isDark ? 'Festival Wifi Guys Logo Dark' : 'Festival Wifi Guys Logo'"
               class="h-24 max-w-[360px] object-contain"/>
     </div>
</div>