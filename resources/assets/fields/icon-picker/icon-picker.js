// modified from https://github.com/dogukanakkaya/iconpicker
class Iconpicker
{
    constructor(el, options) {
        this.el = el
        this.options = options

        // Options
        const hideOnSelect = options?.hideOnSelect ?? true
        const selectedClass = options?.selectedClass ?? 'selected'
        const defaultValue = options?.defaultValue ?? ''

        const icons = options?.icons ?? ["icon-ad","icon-address-book","icon-address-card","icon-adjust","icon-air-freshener","icon-align-center","icon-align-justify","icon-align-left","icon-align-right","icon-allergies","icon-ambulance","icon-american-sign-language-interpreting","icon-anchor","icon-angle-double-down","icon-angle-double-left","icon-angle-double-right","icon-angle-double-up","icon-angle-down","icon-angle-left","icon-angle-right","icon-angle-up","icon-angry","icon-ankh","icon-apple-alt","icon-archive","icon-archway","icon-arrow-alt-circle-down","icon-arrow-alt-circle-left","icon-arrow-alt-circle-right","icon-arrow-alt-circle-up","icon-arrow-circle-down","icon-arrow-circle-left","icon-arrow-circle-right","icon-arrow-circle-up","icon-arrow-down","icon-arrow-left","icon-arrow-right","icon-arrows-alt","icon-arrows-alt-h","icon-arrows-alt-v","icon-arrow-up","icon-assistive-listening-systems","icon-asterisk","icon-at","icon-atlas","icon-atom","icon-audio-description","icon-award","icon-baby","icon-baby-carriage","icon-backspace","icon-backward","icon-bacon","icon-bacteria","icon-bacterium","icon-bahai","icon-balance-scale","icon-balance-scale-left","icon-balance-scale-right","icon-ban","icon-band-aid","icon-barcode","icon-bars","icon-baseball-ball","icon-basketball-ball","icon-bath","icon-battery-empty","icon-battery-full","icon-battery-half","icon-battery-quarter","icon-battery-three-quarters","icon-bed","icon-beer","icon-bell","icon-bell-slash","icon-bezier-curve","icon-bible","icon-bicycle","icon-biking","icon-binoculars","icon-biohazard","icon-birthday-cake","icon-blender","icon-blender-phone","icon-blind","icon-blog","icon-bold","icon-bolt","icon-bomb","icon-bone","icon-bong","icon-book","icon-book-dead","icon-bookmark","icon-book-medical","icon-book-open","icon-book-reader","icon-border-all","icon-border-none","icon-border-style","icon-bowling-ball","icon-box","icon-boxes","icon-box-open","icon-box-tissue","icon-braille","icon-brain","icon-bread-slice","icon-briefcase","icon-briefcase-medical","icon-broadcast-tower","icon-broom","icon-brush","icon-bug","icon-building","icon-bullhorn","icon-bullseye","icon-burn","icon-bus","icon-bus-alt","icon-business-time","icon-calculator","icon-calendar","icon-calendar-alt","icon-calendar-check","icon-calendar-day","icon-calendar-minus","icon-calendar-plus","icon-calendar-times","icon-calendar-week","icon-camera","icon-camera-retro","icon-campground","icon-candy-cane","icon-cannabis","icon-capsules","icon-car","icon-car-alt","icon-caravan","icon-car-battery","icon-car-crash","icon-caret-down","icon-caret-left","icon-caret-right","icon-caret-square-down","icon-caret-square-left","icon-caret-square-right","icon-caret-square-up","icon-caret-up","icon-carrot","icon-car-side","icon-cart-arrow-down","icon-cart-plus","icon-cash-register","icon-cat","icon-certificate","icon-chair","icon-chalkboard","icon-chalkboard-teacher","icon-charging-station","icon-chart-area","icon-chart-bar","icon-chart-line","icon-chart-pie","icon-check","icon-check-circle","icon-check-double","icon-check-square","icon-cheese","icon-chess","icon-chess-bishop","icon-chess-board","icon-chess-king","icon-chess-knight","icon-chess-pawn","icon-chess-queen","icon-chess-rook","icon-chevron-circle-down","icon-chevron-circle-left","icon-chevron-circle-right","icon-chevron-circle-up","icon-chevron-down","icon-chevron-left","icon-chevron-right","icon-chevron-up","icon-child","icon-church","icon-circle","icon-circle-notch","icon-city","icon-clinic-medical","icon-clipboard","icon-clipboard-check","icon-clipboard-list","icon-clock","icon-clone","icon-closed-captioning","icon-cloud","icon-cloud-download-alt","icon-cloud-meatball","icon-cloud-moon","icon-cloud-moon-rain","icon-cloud-rain","icon-cloud-showers-heavy","icon-cloud-sun","icon-cloud-sun-rain","icon-cloud-upload-alt","icon-cocktail","icon-code","icon-code-branch","icon-coffee","icon-cog","icon-cogs","icon-coins","icon-columns","icon-comment","icon-comment-alt","icon-comment-dollar","icon-comment-dots","icon-comment-medical","icon-comments","icon-comments-dollar","icon-comment-slash","icon-compact-disc","icon-compass","icon-compress","icon-compress-alt","icon-compress-arrows-alt","icon-concierge-bell","icon-cookie","icon-cookie-bite","icon-copy","icon-copyright","icon-couch","icon-credit-card","icon-crop","icon-crop-alt","icon-cross","icon-crosshairs","icon-crow","icon-crown","icon-crutch","icon-cube","icon-cubes","icon-cut","icon-database","icon-deaf","icon-democrat","icon-desktop","icon-dharmachakra","icon-diagnoses","icon-dice","icon-dice-d6","icon-dice-d20","icon-dice-five","icon-dice-four","icon-dice-one","icon-dice-six","icon-dice-three","icon-dice-two","icon-digital-tachograph","icon-directions","icon-disease","icon-divide","icon-dizzy","icon-dna","icon-dog","icon-dollar-sign","icon-dolly","icon-dolly-flatbed","icon-donate","icon-door-closed","icon-door-open","icon-dot-circle","icon-dove","icon-download","icon-drafting-compass","icon-dragon","icon-draw-polygon","icon-drum","icon-drum-steelpan","icon-drumstick-bite","icon-dumbbell","icon-dumpster","icon-dumpster-fire","icon-dungeon","icon-edit","icon-egg","icon-eject","icon-ellipsis-h","icon-ellipsis-v","icon-envelope","icon-envelope-open","icon-envelope-open-text","icon-envelope-square","icon-equals","icon-eraser","icon-ethernet","icon-euro-sign","icon-exchange-alt","icon-exclamation","icon-exclamation-circle","icon-exclamation-triangle","icon-expand","icon-expand-alt","icon-expand-arrows-alt","icon-external-link-alt","icon-external-link-square-alt","icon-eye","icon-eye-dropper","icon-eye-slash","icon-fan","icon-fast-backward","icon-fast-forward","icon-faucet","icon-fax","icon-feather","icon-feather-alt","icon-female","icon-fighter-jet","icon-file","icon-file-alt","icon-file-archive","icon-file-audio","icon-file-code","icon-file-contract","icon-file-csv","icon-file-download","icon-file-excel","icon-file-export","icon-file-image","icon-file-import","icon-file-invoice","icon-file-invoice-dollar","icon-file-medical","icon-file-medical-alt","icon-file-pdf","icon-file-powerpoint","icon-file-prescription","icon-file-signature","icon-file-upload","icon-file-video","icon-file-word","icon-fill","icon-fill-drip","icon-film","icon-filter","icon-fingerprint","icon-fire","icon-fire-alt","icon-fire-extinguisher","icon-first-aid","icon-fish","icon-fist-raised","icon-flag","icon-flag-checkered","icon-flag-usa","icon-flask","icon-flushed","icon-folder","icon-folder-minus","icon-folder-open","icon-folder-plus","icon-font","icon-font-awesome-logo-full","icon-football-ball","icon-forward","icon-frog","icon-frown","icon-frown-open","icon-funnel-dollar","icon-futbol","icon-gamepad","icon-gas-pump","icon-gavel","icon-gem","icon-genderless","icon-ghost","icon-gift","icon-gifts","icon-glass-cheers","icon-glasses","icon-glass-martini","icon-glass-martini-alt","icon-glass-whiskey","icon-globe","icon-globe-africa","icon-globe-americas","icon-globe-asia","icon-globe-europe","icon-golf-ball","icon-gopuram","icon-graduation-cap","icon-greater-than","icon-greater-than-equal","icon-grimace","icon-grin","icon-grin-alt","icon-grin-beam","icon-grin-beam-sweat","icon-grin-hearts","icon-grin-squint","icon-grin-squint-tears","icon-grin-stars","icon-grin-tears","icon-grin-tongue","icon-grin-tongue-squint","icon-grin-tongue-wink","icon-grin-wink","icon-grip-horizontal","icon-grip-lines","icon-grip-lines-vertical","icon-grip-vertical","icon-guitar","icon-hamburger","icon-hammer","icon-hamsa","icon-hand-holding","icon-hand-holding-heart","icon-hand-holding-medical","icon-hand-holding-usd","icon-hand-holding-water","icon-hand-lizard","icon-hand-middle-finger","icon-hand-paper","icon-hand-peace","icon-hand-point-down","icon-hand-pointer","icon-hand-point-left","icon-hand-point-right","icon-hand-point-up","icon-hand-rock","icon-hands","icon-hand-scissors","icon-handshake","icon-handshake-alt-slash","icon-handshake-slash","icon-hands-helping","icon-hand-sparkles","icon-hand-spock","icon-hands-wash","icon-hanukiah","icon-hard-hat","icon-hashtag","icon-hat-cowboy","icon-hat-cowboy-side","icon-hat-wizard","icon-hdd","icon-heading","icon-headphones","icon-headphones-alt","icon-headset","icon-head-side-cough","icon-head-side-cough-slash","icon-head-side-mask","icon-head-side-virus","icon-heart","icon-heartbeat","icon-heart-broken","icon-helicopter","icon-highlighter","icon-hiking","icon-hippo","icon-history","icon-hockey-puck","icon-holly-berry","icon-home","icon-horse","icon-horse-head","icon-hospital","icon-hospital-alt","icon-hospital-symbol","icon-hospital-user","icon-hotdog","icon-hotel","icon-hot-tub","icon-hourglass","icon-hourglass-end","icon-hourglass-half","icon-hourglass-start","icon-house-damage","icon-house-user","icon-hryvnia","icon-h-square","icon-ice-cream","icon-icicles","icon-icons","icon-i-cursor","icon-id-badge","icon-id-card","icon-id-card-alt","icon-igloo","icon-image","icon-images","icon-inbox","icon-indent","icon-industry","icon-infinity","icon-info","icon-info-circle","icon-italic","icon-jedi","icon-joint","icon-journal-whills","icon-kaaba","icon-key","icon-keyboard","icon-khanda","icon-kiss","icon-kiss-beam","icon-kiss-wink-heart","icon-kiwi-bird","icon-landmark","icon-language","icon-laptop","icon-laptop-code","icon-laptop-house","icon-laptop-medical","icon-laugh","icon-laugh-beam","icon-laugh-squint","icon-laugh-wink","icon-layer-group","icon-leaf","icon-lemon","icon-less-than","icon-less-than-equal","icon-level-down-alt","icon-level-up-alt","icon-life-ring","icon-lightbulb","icon-link","icon-lira-sign","icon-list","icon-list-alt","icon-list-ol","icon-list-ul","icon-location-arrow","icon-lock","icon-lock-open","icon-long-arrow-alt-down","icon-long-arrow-alt-left","icon-long-arrow-alt-right","icon-long-arrow-alt-up","icon-low-vision","icon-luggage-cart","icon-lungs","icon-lungs-virus","icon-magic","icon-magnet","icon-mail-bulk","icon-male","icon-map","icon-map-marked","icon-map-marked-alt","icon-map-marker","icon-map-marker-alt","icon-map-pin","icon-map-signs","icon-marker","icon-mars","icon-mars-double","icon-mars-stroke","icon-mars-stroke-h","icon-mars-stroke-v","icon-mask","icon-medal","icon-medkit","icon-meh","icon-meh-blank","icon-meh-rolling-eyes","icon-memory","icon-menorah","icon-mercury","icon-meteor","icon-microchip","icon-microphone","icon-microphone-alt","icon-microphone-alt-slash","icon-microphone-slash","icon-microscope","icon-minus","icon-minus-circle","icon-minus-square","icon-mitten","icon-mobile","icon-mobile-alt","icon-money-bill","icon-money-bill-alt","icon-money-bill-wave","icon-money-bill-wave-alt","icon-money-check","icon-money-check-alt","icon-monument","icon-moon","icon-mortar-pestle","icon-mosque","icon-motorcycle","icon-mountain","icon-mouse","icon-mouse-pointer","icon-mug-hot","icon-music","icon-network-wired","icon-neuter","icon-newspaper","icon-not-equal","icon-notes-medical","icon-object-group","icon-object-ungroup","icon-oil-can","icon-om","icon-otter","icon-outdent","icon-pager","icon-paint-brush","icon-paint-roller","icon-palette","icon-pallet","icon-paperclip","icon-paper-plane","icon-parachute-box","icon-paragraph","icon-parking","icon-passport","icon-pastafarianism","icon-paste","icon-pause","icon-pause-circle","icon-paw","icon-peace","icon-pen","icon-pen-alt","icon-pencil-alt","icon-pencil-ruler","icon-pen-fancy","icon-pen-nib","icon-pen-square","icon-people-arrows","icon-people-carry","icon-pepper-hot","icon-percent","icon-percentage","icon-person-booth","icon-phone","icon-phone-alt","icon-phone-slash","icon-phone-square","icon-phone-square-alt","icon-phone-volume","icon-photo-video","icon-piggy-bank","icon-pills","icon-pizza-slice","icon-place-of-worship","icon-plane","icon-plane-arrival","icon-plane-departure","icon-plane-slash","icon-play","icon-play-circle","icon-plug","icon-plus","icon-plus-circle","icon-plus-square","icon-podcast","icon-poll","icon-poll-h","icon-poo","icon-poop","icon-poo-storm","icon-portrait","icon-pound-sign","icon-power-off","icon-pray","icon-praying-hands","icon-prescription","icon-prescription-bottle","icon-prescription-bottle-alt","icon-print","icon-procedures","icon-project-diagram","icon-pump-medical","icon-pump-soap","icon-puzzle-piece","icon-qrcode","icon-question","icon-question-circle","icon-quidditch","icon-quote-left","icon-quote-right","icon-quran","icon-radiation","icon-radiation-alt","icon-rainbow","icon-random","icon-receipt","icon-record-vinyl","icon-recycle","icon-redo","icon-redo-alt","icon-registered","icon-remove-format","icon-reply","icon-reply-all","icon-republican","icon-restroom","icon-retweet","icon-ribbon","icon-ring","icon-road","icon-robot","icon-rocket","icon-route","icon-rss","icon-rss-square","icon-ruble-sign","icon-ruler","icon-ruler-combined","icon-ruler-horizontal","icon-ruler-vertical","icon-running","icon-rupee-sign","icon-sad-cry","icon-sad-tear","icon-satellite","icon-satellite-dish","icon-save","icon-school","icon-screwdriver","icon-scroll","icon-sd-card","icon-search","icon-search-dollar","icon-search-location","icon-search-minus","icon-search-plus","icon-seedling","icon-server","icon-shapes","icon-share","icon-share-alt","icon-share-alt-square","icon-share-square","icon-shekel-sign","icon-shield-alt","icon-shield-virus","icon-ship","icon-shipping-fast","icon-shoe-prints","icon-shopping-bag","icon-shopping-basket","icon-shopping-cart","icon-shower","icon-shuttle-van","icon-sign","icon-signal","icon-signature","icon-sign-in-alt","icon-sign-language","icon-sign-out-alt","icon-sim-card","icon-sink","icon-sitemap","icon-skating","icon-skiing","icon-skiing-nordic","icon-skull","icon-skull-crossbones","icon-slash","icon-sleigh","icon-sliders-h","icon-smile","icon-smile-beam","icon-smile-wink","icon-smog","icon-smoking","icon-smoking-ban","icon-sms","icon-snowboarding","icon-snowflake","icon-snowman","icon-snowplow","icon-soap","icon-socks","icon-solar-panel","icon-sort","icon-sort-alpha-down","icon-sort-alpha-down-alt","icon-sort-alpha-up","icon-sort-alpha-up-alt","icon-sort-amount-down","icon-sort-amount-down-alt","icon-sort-amount-up","icon-sort-amount-up-alt","icon-sort-down","icon-sort-numeric-down","icon-sort-numeric-down-alt","icon-sort-numeric-up","icon-sort-numeric-up-alt","icon-sort-up","icon-spa","icon-space-shuttle","icon-spell-check","icon-spider","icon-spinner","icon-splotch","icon-spray-can","icon-square","icon-square-full","icon-square-root-alt","icon-stamp","icon-star","icon-star-and-crescent","icon-star-half","icon-star-half-alt","icon-star-of-david","icon-star-of-life","icon-step-backward","icon-step-forward","icon-stethoscope","icon-sticky-note","icon-stop","icon-stop-circle","icon-stopwatch","icon-stopwatch-20","icon-store","icon-store-alt","icon-store-alt-slash","icon-store-slash","icon-stream","icon-street-view","icon-strikethrough","icon-stroopwafel","icon-subscript","icon-subway","icon-suitcase","icon-suitcase-rolling","icon-sun","icon-superscript","icon-surprise","icon-swatchbook","icon-swimmer","icon-swimming-pool","icon-synagogue","icon-sync","icon-sync-alt","icon-syringe","icon-table","icon-tablet","icon-tablet-alt","icon-table-tennis","icon-tablets","icon-tachometer-alt","icon-tag","icon-tags","icon-tape","icon-tasks","icon-taxi","icon-teeth","icon-teeth-open","icon-temperature-high","icon-temperature-low","icon-tenge","icon-terminal","icon-text-height","icon-text-width","icon-th","icon-theater-masks","icon-thermometer","icon-thermometer-empty","icon-thermometer-full","icon-thermometer-half","icon-thermometer-quarter","icon-thermometer-three-quarters","icon-th-large","icon-th-list","icon-thumbs-down","icon-thumbs-up","icon-thumbtack","icon-ticket-alt","icon-times","icon-times-circle","icon-tint","icon-tint-slash","icon-tired","icon-toggle-off","icon-toggle-on","icon-toilet","icon-toilet-paper","icon-toilet-paper-slash","icon-toolbox","icon-tools","icon-tooth","icon-torah","icon-torii-gate","icon-tractor","icon-trademark","icon-traffic-light","icon-trailer","icon-train","icon-tram","icon-transgender","icon-transgender-alt","icon-trash","icon-trash-alt","icon-trash-restore","icon-trash-restore-alt","icon-tree","icon-trophy","icon-truck","icon-truck-loading","icon-truck-monster","icon-truck-moving","icon-truck-pickup","icon-tshirt","icon-tty","icon-tv","icon-umbrella","icon-umbrella-beach","icon-underline","icon-undo","icon-undo-alt","icon-universal-access","icon-university","icon-unlink","icon-unlock","icon-unlock-alt","icon-upload","icon-user","icon-user-alt","icon-user-alt-slash","icon-user-astronaut","icon-user-check","icon-user-circle","icon-user-clock","icon-user-cog","icon-user-edit","icon-user-friends","icon-user-graduate","icon-user-injured","icon-user-lock","icon-user-md","icon-user-minus","icon-user-ninja","icon-user-nurse","icon-user-plus","icon-users","icon-users-cog","icon-user-secret","icon-user-shield","icon-user-slash","icon-users-slash","icon-user-tag","icon-user-tie","icon-user-times","icon-utensils","icon-utensil-spoon","icon-vector-square","icon-venus","icon-venus-double","icon-venus-mars","icon-vest","icon-vest-patches","icon-vial","icon-vials","icon-video","icon-video-slash","icon-vihara","icon-virus","icon-viruses","icon-virus-slash","icon-voicemail","icon-volleyball-ball","icon-volume-down","icon-volume-mute","icon-volume-off","icon-volume-up","icon-vote-yea","icon-vr-cardboard","icon-walking","icon-wallet","icon-warehouse","icon-water","icon-wave-square","icon-weight","icon-weight-hanging","icon-wheelchair","icon-wifi","icon-wind","icon-window-close","icon-window-maximize","icon-window-minimize","icon-window-restore","icon-wine-bottle","icon-wine-glass","icon-wine-glass-alt","icon-won-sign","icon-wrench","icon-x-ray","icon-yen-sign","icon-yin-yang"]

        if (options?.searchable ?? true){
            this.el.addEventListener('keyup', this.search)
        }

        if (typeof options?.valueFormat === "function") {
            this.valueFormat = options.valueFormat
        }else{
            this.valueFormat = val => `${val}`
        }

        this.el.insertAdjacentHTML('afterend', `
                <div class="iconpicker-dropdown ${options?.containerClass ?? ''}">
                    <ul>
                        ${icons.map(icon => `<li value="${this.valueFormat(icon)}" class="${defaultValue === icon ? selectedClass : ''}"><i class="${this.valueFormat(icon)}"></i></li>`).join('')}
                    </ul>
                </div>
            `)

        this.el.nextElementSibling.querySelectorAll('li').forEach(item => item.addEventListener('click', e => {
            this.el.nextElementSibling.querySelector('li.selected')?.classList.remove(selectedClass)
            item.classList.add(selectedClass)
            const value = item.getAttribute('value')
            this.el.value = value
            if(hideOnSelect){
                this.el.nextElementSibling.classList.remove('show')
            }
            if (this.options?.showSelectedIn){
                this.options.showSelectedIn.innerHTML = `<i class="${value}"></i>`
            }
        }))

        this.el.addEventListener('focusin', this.focusIn)
        this.el.addEventListener('change', this.setIconOnChange)

        this.el.value = this.valueFormat(defaultValue)
        this.el.dispatchEvent(new Event('change'))
    }

    /**
     * Use input as a search box
     */
    search() {
        const items = this.nextElementSibling.getElementsByTagName('li')
        const pattern = new RegExp(this.value, 'i');

        for (let i=0; i < items.length; i++) {
            const item = items[i];
            if (pattern.test(item.getAttribute('value'))) {
                item.classList.remove("hidden");
            } else {
                item.classList.add("hidden");
            }
        }
    }

    /**
     * if showSelectedIn argument passed show icon in that element
     */
    setIconOnChange = () => {
        if (this.options?.showSelectedIn){
            this.options.showSelectedIn.innerHTML = `<i class="${this.el.value}"></i>`
        }
    }

    /**
     * Focus event for given input element
     */
    focusIn = () => {
        if(this.el.nextElementSibling?.classList?.contains('iconpicker-dropdown')){
            this.el.nextElementSibling.style.top = this.el.offsetHeight + 'px'
            this.el.nextElementSibling.classList.add('show')
        }
    }

    /**
     * Reset the iconpicker instance
     * @param {*} setValue
     */
    set = (setValue = '') => {
        this.el.value = this.valueFormat(setValue)
        this.setIconOnChange(this.valueFormat(setValue))
    }
}

window.Iconpicker = Iconpicker

/**
 * Close iconpicker on click outside
 */
document.addEventListener('click', e => {
    document.querySelectorAll('.iconpicker-dropdown').forEach(dw => {
        const isClickInside = dw.contains(e.target) || dw.previousElementSibling.contains(e.target)

        if (!isClickInside) {
            dw.classList.remove('show')
        }
    })
});
