<div class="max-w-3xl mx-auto px-4 py-20" x-data="createEventWizard()">
    
    <!-- Header/Stepper -->
    <div class="mb-12">
        <h1 class="serif-heading text-4xl font-bold text-charcoal-900 mb-6">Host a new experience</h1>
        <div class="flex items-center gap-4">
            <template x-for="i in [1, 2, 3]">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold transition border-2"
                        :class="step >= i ? 'bg-brand-500 border-brand-500 text-white shadow-lg shadow-brand-500/30' : 'bg-white border-charcoal-200 text-charcoal-400'">
                        <span x-text="i"></span>
                    </div>
                    <div x-show="i < 3" class="w-12 h-0.5 bg-charcoal-200 rounded-full">
                        <div class="h-full bg-brand-500 transition-all duration-500 rounded-full" :style="'width: ' + (step > i ? '100%' : '0%')"></div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Step 1: Basic Info -->
    <div x-show="step === 1" x-transition.opacity class="space-y-8">
        <div class="bg-white p-8 rounded-[2rem] border-2 border-charcoal-100 shadow-xl shadow-charcoal-900/5 space-y-6">
            <div>
                <label class="block text-xs font-bold text-charcoal-600 uppercase tracking-widest mb-2">Event Title</label>
                <input type="text" x-model="formData.title" placeholder="e.g. Summer Solstice Party" 
                    class="w-full bg-cream-50 border-2 border-charcoal-200 rounded-xl px-4 py-4 text-charcoal-900 focus:border-brand-500 outline-none transition text-xl font-bold placeholder-charcoal-300">
            </div>

            <div>
                <label class="block text-xs font-bold text-charcoal-600 uppercase tracking-widest mb-2">Category</label>
                <div class="relative">
                    <select x-model="formData.category" class="w-full bg-cream-50 border-2 border-charcoal-200 rounded-xl px-4 py-4 text-charcoal-900 focus:border-brand-500 outline-none appearance-none font-medium">
                        <option value="Social">Social</option>
                        <option value="Tech">Tech</option>
                        <option value="Music">Music</option>
                        <option value="Art">Art</option>
                        <option value="Food">Food</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-charcoal-400 pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-charcoal-600 uppercase tracking-widest mb-2">Cover Image</label>
                <div class="flex gap-4 items-center">
                    <div class="w-32 h-20 bg-cream-50 rounded-xl border-2 border-dashed border-charcoal-300 flex items-center justify-center overflow-hidden group hover:border-brand-400 transition cursor-pointer">
                        <template x-if="formData.image">
                            <img :src="formData.image" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!formData.image">
                            <i data-lucide="image" class="text-charcoal-300 group-hover:text-brand-500 transition"></i>
                        </template>
                    </div>
                    <div class="space-y-2">
                        <button @click="generateAIImage()" class="text-xs flex items-center gap-2 bg-purple-50 text-purple-600 px-4 py-2 rounded-xl border-2 border-purple-100 hover:border-purple-200 hover:bg-purple-100 transition font-bold">
                            <i data-lucide="wand-2" class="w-4 h-4"></i> Generate with AI
                        </button>
                        <p class="text-[10px] text-charcoal-500 font-medium">Pick a prompt to create a unique poster</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 2: Location & Time -->
    <div x-show="step === 2" x-transition.opacity class="space-y-8">
        <div class="bg-white p-8 rounded-[2rem] border-2 border-charcoal-100 shadow-xl shadow-charcoal-900/5 space-y-6">
             <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-charcoal-600 uppercase tracking-widest mb-2">Date</label>
                    <input type="date" x-model="formData.date" class="w-full bg-cream-50 border-2 border-charcoal-200 rounded-xl px-4 py-3 text-charcoal-900 focus:border-brand-500 outline-none transition font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-charcoal-600 uppercase tracking-widest mb-2">Time</label>
                    <input type="time" x-model="formData.time" class="w-full bg-cream-50 border-2 border-charcoal-200 rounded-xl px-4 py-3 text-charcoal-900 focus:border-brand-500 outline-none transition font-medium">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-charcoal-600 uppercase tracking-widest mb-2">Venue Name</label>
                <input type="text" x-model="formData.location_name" placeholder="e.g. Central Park, Main Stage" 
                    class="w-full bg-cream-50 border-2 border-charcoal-200 rounded-xl px-4 py-3 text-charcoal-900 focus:border-brand-500 outline-none transition font-medium">
            </div>
        </div>
    </div>

    <!-- Step 3: Logistics & Tiers -->
    <div x-show="step === 3" x-transition.opacity class="space-y-8">
        <div class="bg-white p-8 rounded-[2rem] border-2 border-charcoal-100 shadow-xl shadow-charcoal-900/5 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-charcoal-900 text-lg">Ticket Tiers</h3>
                <button @click="addTier()" class="text-xs bg-brand-600 text-white px-4 py-2 rounded-xl flex items-center gap-1 font-bold shadow-lg shadow-brand-500/20 hover:bg-brand-700 transition">
                    <i data-lucide="plus" class="w-3 h-3"></i> Add Tier
                </button>
            </div>
            
            <template x-for="(tier, index) in formData.tiers" :key="index">
                <div class="p-4 bg-cream-50 rounded-2xl border-2 border-charcoal-100 relative group">
                    <button @click="removeTier(index)" class="absolute top-2 right-2 text-charcoal-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                    </button>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" x-model="tier.name" placeholder="Tier Name (e.g. VIP)" class="bg-transparent border-b-2 border-charcoal-200 text-sm text-charcoal-900 focus:border-brand-500 outline-none py-2 font-bold placeholder-charcoal-400">
                        <input type="text" x-model="tier.price" placeholder="Price ($)" class="bg-transparent border-b-2 border-charcoal-200 text-sm text-charcoal-900 focus:border-brand-500 outline-none py-2 font-mono placeholder-charcoal-400 text-right">
                    </div>
                </div>
            </template>

            <hr class="border-charcoal-100">

            <div class="flex items-center justify-between bg-cream-50 p-4 rounded-2xl border-2 border-charcoal-100">
                <div>
                   <h3 class="font-bold text-charcoal-900 mb-1 flex items-center gap-2">
                       <i data-lucide="shield-check" class="w-4 h-4 text-brand-600"></i> Approval Only
                   </h3>
                   <p class="text-xs text-charcoal-500">Guests must be approved before they can join</p>
                </div>
                <button @click="formData.approval = !formData.approval" 
                    class="w-12 h-7 rounded-full transition relative p-1 shadow-inner"
                    :class="formData.approval ? 'bg-brand-500' : 'bg-charcoal-200'">
                    <div class="w-5 h-5 bg-white rounded-full transition-transform shadow-sm" :class="formData.approval ? 'translate-x-5' : 'translate-x-0'"></div>
                </button>
            </div>

            <div>
                <label class="block text-xs font-bold text-charcoal-600 uppercase tracking-widest mb-2">Custom Questions</label>
                <div class="text-[10px] text-charcoal-500 mb-2 font-medium">Collect info like dietary requirements or T-shirt size.</div>
                <input type="text" x-model="formData.question" placeholder="Add a question..." 
                    class="w-full bg-cream-50 border-2 border-charcoal-200 rounded-xl px-4 py-3 text-charcoal-900 focus:border-brand-500 outline-none text-sm transition">
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="mt-12 flex justify-between items-center">
        <button x-show="step > 1" @click="step--" class="px-6 py-3 text-charcoal-500 hover:text-charcoal-900 transition font-bold text-sm uppercase tracking-wider">Back</button>
        <div x-show="step === 1"></div> <!-- Filler -->
        
        <button x-show="step < 3" @click="nextStep()" class="px-10 py-4 bg-brand-600 text-white rounded-full font-bold shadow-xl shadow-brand-500/20 hover:scale-[1.02] transition flex items-center gap-2">
            Next Step <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
        <button x-show="step === 3" @click="submitEvent()" class="px-10 py-4 bg-charcoal-900 text-white rounded-full font-bold shadow-xl hover:scale-[1.02] transition flex items-center gap-2">
            Publish Event <i data-lucide="rocket" class="w-4 h-4"></i>
        </button>
    </div>

</div>

<script>
function createEventWizard() {
    return {
        step: 1,
        map: null,
        marker: null,
        formData: {
            title: '',
            category: 'Social',
            image: '',
            date: '',
            time: '',
            location_name: '',
            lat: 40.7128,
            lng: -74.0060,
            tiers: [{name: 'General Admission', price: 'Free'}],
            approval: true,
            question: ''
        },

        init() {
            lucide.createIcons();
        },

        nextStep() {
            this.step++;
        },

        generateAIImage() {
            // Simulated AI Generation using local tool logic
            this.formData.image = 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?auto=format&fit=crop&w=800&q=80';
            alert("Rich AI Image Generated based on Title!");
        },

        addTier() {
            this.formData.tiers.push({name: '', price: ''});
            setTimeout(() => lucide.createIcons(), 10);
        },

        removeTier(index) {
            this.formData.tiers.splice(index, 1);
        },

        submitEvent() {
            const formData = new FormData();
            formData.append('title', this.formData.title);
            formData.append('category', this.formData.category);
            formData.append('image', this.formData.image);
            formData.append('date', this.formData.date);
            formData.append('time', this.formData.time);
            formData.append('location_name', this.formData.location_name);
            formData.append('latitude', this.formData.lat);
            formData.append('longitude', this.formData.lng);
            formData.append('description', 'Join us for ' + this.formData.title + '! A ' + this.formData.category + ' event.');
            
            // Append Tiers as JSON string
            formData.append('tiers', JSON.stringify(this.formData.tiers));
            // Append Approval Setting (1 or 0)
            formData.append('requires_approval', this.formData.approval ? 1 : 0);

            fetch('<?php echo BASE_URL; ?>events/create', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert("Event Published Successfully!");
                    window.location.href = data.redirect;
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Something went wrong. Please try again.");
            });
        }
    }
}
</script>
