<?php
// Demo Data if DB is empty for First Run Visualization
if (empty($events)) {
    $events = [
        [
            'id' => 1,
            'title' => 'Neon Nights: Rooftop Party',
            'start_time' => date('Y-m-d H:i:s', strtotime('+2 days 20:00')),
            'location_name' => 'Skyline Lounge, NYC',
            'image_url' => 'https://images.unsplash.com/photo-1545128485-c400e7702796?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
            'category' => 'Nightlife'
        ],
        [
            'id' => 2,
            'title' => 'Tech Innovators Summit',
            'start_time' => date('Y-m-d H:i:s', strtotime('+5 days 09:00')),
            'location_name' => 'Convention Center',
            'image_url' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
            'category' => 'Tech'
        ],
        [
            'id' => 3,
            'title' => 'Urban Art Workshop',
            'start_time' => date('Y-m-d H:i:s', strtotime('+1 week 14:00')),
            'location_name' => 'The Loft Studio',
            'image_url' => 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
            'category' => 'Art'
        ]
    ];
}
?>

<!-- Hero Section -->
<div class="relative w-full h-[700px] flex items-center justify-center overflow-hidden bg-gradient-to-br from-cream-50 via-white to-brand-50">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-10 w-72 h-72 bg-brand-300 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-brand-200 rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative z-20 text-center px-4 max-w-4xl mx-auto">
        <span class="inline-block py-2 px-4 rounded-full bg-brand-100 border border-brand-300 text-brand-700 text-sm font-semibold mb-6">
            Discover & Host Events
        </span>
        <h1 class="serif-heading text-5xl md:text-7xl font-bold text-charcoal-900 mb-6 leading-tight tracking-tight">
            Unforgettable <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-brand-700">Experiences</span> Await
        </h1>
        <p class="text-xl text-charcoal-600 mb-10 max-w-2xl mx-auto font-light">
            Join the community. Find events that match your vibe, or host your own and build a following.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#events" class="px-8 py-4 bg-brand-500 text-white font-bold rounded-full hover:bg-brand-600 transition transform hover:scale-105 shadow-lg shadow-brand-500/30">Explore Events Around</a>
            <a href="<?php echo BASE_URL; ?>events/create" class="px-8 py-4 bg-white text-brand-600 font-bold rounded-full hover:bg-cream-100 transition transform hover:scale-105 shadow-lg border-2 border-brand-500">Host an Event</a>
        </div>
    </div>
</div>

<!-- All Events Section -->
<div id="events" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex items-center justify-between mb-12">
        <h2 class="serif-heading text-4xl font-bold text-charcoal-900">Upcoming Events</h2>
        <a href="<?php echo BASE_URL; ?>explore" class="text-brand-600 hover:text-brand-700 font-medium flex items-center gap-2">
            View all events 
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach($events as $event): ?>
        <div class="group bg-white rounded-3xl overflow-hidden border-2 border-charcoal-200 hover:border-brand-400 transition duration-500 hover:shadow-xl hover:shadow-brand-500/10 hover:-translate-y-1">
            <div class="relative h-64 overflow-hidden">
                <img src="<?php echo $event['image_url'] ?? 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm text-brand-700 text-xs font-bold px-3 py-1.5 rounded-full border border-brand-300 shadow-sm">
                    <?php echo htmlspecialchars($event['category'] ?? ''); ?>
                </div>
            </div>
            <div class="p-8">
                <div class="flex items-center gap-2 text-brand-600 text-sm font-bold mb-3">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <?php echo date('M d, Y • g:i A', strtotime($event['start_time'])); ?>
                </div>
                <h3 class="serif-heading text-2xl font-bold text-charcoal-900 mb-4 group-hover:text-brand-600 transition"><?php echo htmlspecialchars($event['title'] ?? ''); ?></h3>
                <p class="text-charcoal-600 mb-6 line-clamp-2"><?php echo htmlspecialchars($event['description'] ?? ''); ?></p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($event['organizer_name'] ?? 'Organizer'); ?>&background=random" class="w-8 h-8 rounded-full border-2 border-charcoal-200">
                        <span class="text-charcoal-500 text-sm">by <?php echo htmlspecialchars($event['organizer_name'] ?? 'Organizer'); ?></span>
                    </div>
                    <a href="<?php echo BASE_URL; ?>event/<?php echo $event['id']; ?>" class="px-6 py-2 bg-brand-50 hover:bg-brand-100 text-brand-700 rounded-full text-sm font-bold transition border border-brand-300">Details</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- What's Included in Your Ritual Kit Section -->
<div class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-brand-600 text-xs font-bold uppercase tracking-widest mb-3">Digital Package</p>
            <h2 class="serif-heading text-4xl md:text-5xl font-bold text-charcoal-900 mb-4">What's Included in Your Ritual Kit</h2>
            <p class="text-charcoal-600 text-lg max-w-2xl mx-auto">Everything you need to reflect, release, and renew. Delivered instantly to your inbox upon registration.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Item 1 -->
            <div class="bg-cream-50 p-6 rounded-2xl border-2 border-charcoal-200 hover:border-brand-400 hover:shadow-lg transition duration-300">
                <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="book-open" class="w-7 h-7 text-brand-600"></i>
                </div>
                <h3 class="font-bold text-charcoal-900 text-lg mb-2">Step-by-Step Ritual Guide</h3>
                <p class="text-charcoal-600 text-sm">A complete walkthrough of the evening</p>
            </div>
            
            <!-- Item 2 -->
            <div class="bg-cream-50 p-6 rounded-2xl border-2 border-charcoal-200 hover:border-brand-400 hover:shadow-lg transition duration-300">
                <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="file-text" class="w-7 h-7 text-brand-600"></i>
                </div>
                <h3 class="font-bold text-charcoal-900 text-lg mb-2">Letting Go Sheet</h3>
                <p class="text-charcoal-600 text-sm">Release old habits and limiting beliefs</p>
            </div>
            
            <!-- Item 3 -->
            <div class="bg-cream-50 p-6 rounded-2xl border-2 border-charcoal-200 hover:border-brand-400 hover:shadow-lg transition duration-300">
                <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="calendar" class="w-7 h-7 text-brand-600"></i>
                </div>
                <h3 class="font-bold text-charcoal-900 text-lg mb-2">Year in Review Memory Map</h3>
                <p class="text-charcoal-600 text-sm">Visualize your journey through the last 12 months</p>
            </div>
            
            <!-- Item 4 -->
            <div class="bg-cream-50 p-6 rounded-2xl border-2 border-charcoal-200 hover:border-brand-400 hover:shadow-lg transition duration-300">
                <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="target" class="w-7 h-7 text-brand-600"></i>
                </div>
                <h3 class="font-bold text-charcoal-900 text-lg mb-2">Resolution Cards</h3>
                <p class="text-charcoal-600 text-sm">Set meaningful goals with actionable steps</p>
            </div>
            
            <!-- Item 5 -->
            <div class="bg-cream-50 p-6 rounded-2xl border-2 border-charcoal-200 hover:border-brand-400 hover:shadow-lg transition duration-300">
                <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="award" class="w-7 h-7 text-brand-600"></i>
                </div>
                <h3 class="font-bold text-charcoal-900 text-lg mb-2">Legacy Certificate</h3>
                <p class="text-charcoal-600 text-sm">Commit to your intentions for the year ahead</p>
            </div>
            
            <!-- Item 6 -->
            <div class="bg-cream-50 p-6 rounded-2xl border-2 border-charcoal-200 hover:border-brand-400 hover:shadow-lg transition duration-300">
                <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="sparkles" class="w-7 h-7 text-brand-600"></i>
                </div>
                <h3 class="font-bold text-charcoal-900 text-lg mb-2">Affirmation Cards</h3>
                <p class="text-charcoal-600 text-sm">Daily reminders of your power and purpose</p>
            </div>
            
            <!-- Item 7 -->
            <div class="bg-cream-50 p-6 rounded-2xl border-2 border-charcoal-200 hover:border-brand-400 hover:shadow-lg transition duration-300">
                <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="check-square" class="w-7 h-7 text-brand-600"></i>
                </div>
                <h3 class="font-bold text-charcoal-900 text-lg mb-2">Preparation Checklist</h3>
                <p class="text-charcoal-600 text-sm">Everything you need to be ready</p>
            </div>
            
            <!-- Item 8 -->
            <div class="bg-cream-50 p-6 rounded-2xl border-2 border-charcoal-200 hover:border-brand-400 hover:shadow-lg transition duration-300">
                <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="camera" class="w-7 h-7 text-brand-600"></i>
                </div>
                <h3 class="font-bold text-charcoal-900 text-lg mb-2">Photo Prompt</h3>
                <p class="text-charcoal-600 text-sm">Capture the magic of the moment</p>
            </div>
            
            <!-- Item 9 -->
            <div class="bg-cream-50 p-6 rounded-2xl border-2 border-charcoal-200 hover:border-brand-400 hover:shadow-lg transition duration-300">
                <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="mail" class="w-7 h-7 text-brand-600"></i>
                </div>
                <h3 class="font-bold text-charcoal-900 text-lg mb-2">Invitation Template</h3>
                <p class="text-charcoal-600 text-sm">Invite loved ones to join you</p>
            </div>
        </div>
    </div>
</div>

<!-- Host Your Own Section -->
<div class="py-20 bg-gradient-to-br from-brand-500 to-brand-600 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
    </div>
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="serif-heading text-4xl md:text-5xl font-bold text-white mb-6">Ready to Host Your Own Event?</h2>
        <p class="text-brand-50 text-xl mb-10">Join hundreds of organizers who use EventHorizons to build their communities and host unforgettable experiences.</p>
        <a href="<?php echo BASE_URL; ?>events/create" class="inline-flex items-center gap-3 px-10 py-5 bg-white text-brand-700 font-bold rounded-full hover:bg-cream-100 transition transform hover:scale-105 shadow-2xl">
            <i data-lucide="plus-circle" class="w-6 h-6"></i>
            Start Hosting Now
        </a>
    </div>
</div>

<!-- RSVP Flow (Refined for specific events) -->
<div id="rsvp" class="py-20 bg-cream-100">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="serif-heading text-4xl font-bold text-charcoal-900 mb-6">Quick RSVP</h2>
        <p class="text-charcoal-600 mb-10 text-lg">Select an event and apply for approval to get your digital kit.</p>
        
        <form action="<?php echo BASE_URL; ?>rsvp/submit" method="POST" class="bg-white p-8 rounded-3xl border-2 border-charcoal-200 text-left shadow-lg">
            <div class="mb-6">
                <label class="block text-sm font-semibold text-charcoal-700 mb-2">Select Event</label>
                <select name="event_id" required class="w-full px-4 py-3 bg-cream-50 rounded-xl border-2 border-charcoal-200 text-charcoal-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition">
                    <?php foreach($events as $event): ?>
                    <option value="<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['title'] ?? ''); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-charcoal-700 mb-2">Full Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 bg-cream-50 rounded-xl border-2 border-charcoal-200 text-charcoal-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition" placeholder="John Doe">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-charcoal-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" required class="w-full px-4 py-3 bg-cream-50 rounded-xl border-2 border-charcoal-200 text-charcoal-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition" placeholder="+1 (555) 000-0000">
                </div>
            </div>
            <div class="mb-8">
                <label class="block text-sm font-semibold text-charcoal-700 mb-2">What interests you most?</label>
                <select name="interest" class="w-full px-4 py-3 bg-cream-50 rounded-xl border-2 border-charcoal-200 text-charcoal-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition">
                    <option>The Experience</option>
                    <option>Networking</option>
                    <option>Learning</option>
                    <option>Entertainment</option>
                </select>
            </div>
            <button type="submit" class="w-full py-4 bg-brand-500 text-white font-bold rounded-xl hover:bg-brand-600 transition transform hover:scale-[1.02] shadow-lg shadow-brand-500/20">
                Submit RSVP Application
            </button>
        </form>


    </div>
</div>

<!-- FAQ Section -->
<div class="py-24 bg-white border-t border-charcoal-200" x-data="{ show: false }" x-intersect="show = true">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" x-show="show" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 transform translate-y-10" x-transition:enter-end="opacity-100 transform translate-y-0">
            <h2 class="serif-heading text-4xl font-bold text-charcoal-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-charcoal-600 text-lg">Everything you need to know about the EventHorizons experience.</p>
        </div>

        <div x-data="{ active: null }" class="space-y-4">
            <!-- FAQ Item 1 -->
            <div class="bg-cream-50 backdrop-blur-xl rounded-2xl border-2 border-charcoal-200 overflow-hidden transition duration-500 hover:border-brand-400 hover:shadow-lg hover:shadow-brand-500/10"
                 x-show="show" x-transition:enter="transition ease-out duration-700 delay-100" x-transition:enter-start="opacity-0 transform translate-y-10" x-transition:enter-end="opacity-100 transform translate-y-0">
                <button @click="active = (active === 1 ? null : 1)" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                    <span class="text-lg font-semibold text-charcoal-900 group-hover:text-brand-600 transition">How do I get my Digital Event Kit?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-charcoal-500 transition-transform duration-300" :class="active === 1 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 1" x-collapse x-cloak class="px-8 pb-6 text-charcoal-600 leading-relaxed border-t border-charcoal-200 pt-4">
                    Once your RSVP is approved by the event organizer, you will receive an automated email with your personalized Digital Event Kit attached as a PDF. You can also download it directly from your profile dashboard.
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-cream-50 backdrop-blur-xl rounded-2xl border-2 border-charcoal-200 overflow-hidden transition duration-500 hover:border-brand-400 hover:shadow-lg hover:shadow-brand-500/10"
                 x-show="show" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 transform translate-y-10" x-transition:enter-end="opacity-100 transform translate-y-0">
                <button @click="active = (active === 2 ? null : 2)" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                    <span class="text-lg font-semibold text-charcoal-900 group-hover:text-brand-600 transition">Is the QR code mandatory for entry?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-charcoal-500 transition-transform duration-300" :class="active === 2 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 2" x-collapse x-cloak class="px-8 pb-6 text-charcoal-600 leading-relaxed border-t border-charcoal-200 pt-4">
                    Yes, absolutely. The QR code found on Page 2 of your Digital Kit is your unique authentication key. Our staff will scan this at the entrance to verify your approval and grant you access to the event.
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-cream-50 backdrop-blur-xl rounded-2xl border-2 border-charcoal-200 overflow-hidden transition duration-500 hover:border-brand-400 hover:shadow-lg hover:shadow-brand-500/10"
                 x-show="show" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 transform translate-y-10" x-transition:enter-end="opacity-100 transform translate-y-0">
                <button @click="active = (active === 3 ? null : 3)" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                    <span class="text-lg font-semibold text-charcoal-900 group-hover:text-brand-600 transition">Can I host my own events here?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-charcoal-500 transition-transform duration-300" :class="active === 3 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 3" x-collapse x-cloak class="px-8 pb-6 text-charcoal-600 leading-relaxed border-t border-charcoal-200 pt-4">
                    Yes! EventHorizons is built for both attendees and organizers. Simply click on "Host an Event" in the navigation bar to start creating your own community and managing your guest lists.
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-cream-50 backdrop-blur-xl rounded-2xl border-2 border-charcoal-200 overflow-hidden transition duration-500 hover:border-brand-400 hover:shadow-lg hover:shadow-brand-500/10"
                 x-show="show" x-transition:enter="transition ease-out duration-700 delay-400" x-transition:enter-start="opacity-0 transform translate-y-10" x-transition:enter-end="opacity-100 transform translate-y-0">
                <button @click="active = (active === 4 ? null : 4)" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                    <span class="text-lg font-semibold text-charcoal-900 group-hover:text-brand-600 transition">What if I don't receive my approval email?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-charcoal-500 transition-transform duration-300" :class="active === 4 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 4" x-collapse x-cloak class="px-8 pb-6 text-charcoal-600 leading-relaxed border-t border-charcoal-200 pt-4">
                    First, check your spam or junk folder. If it's not there, log in to your EventHorizons account and visit your profile. If your status is "Approved," you will see a download link for your kit right there.
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="bg-cream-50 backdrop-blur-xl rounded-2xl border-2 border-charcoal-200 overflow-hidden transition duration-500 hover:border-brand-400 hover:shadow-lg hover:shadow-brand-500/10"
                 x-show="show" x-transition:enter="transition ease-out duration-700 delay-500" x-transition:enter-start="opacity-0 transform translate-y-10" x-transition:enter-end="opacity-100 transform translate-y-0">
                <button @click="active = (active === 5 ? null : 5)" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                    <span class="text-lg font-semibold text-charcoal-900 group-hover:text-brand-600 transition">Can I invite friends to an event I'm attending?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-charcoal-500 transition-transform duration-300" :class="active === 5 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 5" x-collapse x-cloak class="px-8 pb-6 text-charcoal-600 leading-relaxed border-t border-charcoal-200 pt-4">
                    Yes! Once your RSVP is approved, you'll gain access to the "Refer" tab on the event details page. You can use this to send a formal invitation email to your friends so they can join you at the event.
                </div>
            </div>

            <!-- FAQ Item 6 -->
            <div class="bg-cream-50 backdrop-blur-xl rounded-2xl border-2 border-charcoal-200 overflow-hidden transition duration-500 hover:border-brand-400 hover:shadow-lg hover:shadow-brand-500/10"
                 x-show="show" x-transition:enter="transition ease-out duration-700 delay-600" x-transition:enter-start="opacity-0 transform translate-y-10" x-transition:enter-end="opacity-100 transform translate-y-0">
                <button @click="active = (active === 6 ? null : 6)" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                    <span class="text-lg font-semibold text-charcoal-900 group-hover:text-brand-600 transition">How does the community chat work?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-charcoal-500 transition-transform duration-300" :class="active === 6 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 6" x-collapse x-cloak class="px-8 pb-6 text-charcoal-600 leading-relaxed border-t border-charcoal-200 pt-4">
                    The community chat is an exclusive space for approved attendees and organizers to interact before, during, and after the event. It's a great place to network, ask questions, and share excitement.
                </div>
            </div>

            <!-- FAQ Item 7 -->
            <div class="bg-cream-50 backdrop-blur-xl rounded-2xl border-2 border-charcoal-200 overflow-hidden transition duration-500 hover:border-brand-400 hover:shadow-lg hover:shadow-brand-500/10"
                 x-show="show" x-transition:enter="transition ease-out duration-700 delay-700" x-transition:enter-start="opacity-0 transform translate-y-10" x-transition:enter-end="opacity-100 transform translate-y-0">
                <button @click="active = (active === 7 ? null : 7)" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                    <span class="text-lg font-semibold text-charcoal-900 group-hover:text-brand-600 transition">What are "Memories" in the event page?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-charcoal-500 transition-transform duration-300" :class="active === 7 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 7" x-collapse x-cloak class="px-8 pb-6 text-charcoal-600 leading-relaxed border-t border-charcoal-200 pt-4">
                    Memories is a shared photo gallery where approved attendees can upload and view photos from the event. It serves as a digital scrapbook for the community to relive the best moments together.
                </div>
            </div>

            <!-- FAQ Item 8 -->
            <div class="bg-cream-50 backdrop-blur-xl rounded-2xl border-2 border-charcoal-200 overflow-hidden transition duration-500 hover:border-brand-400 hover:shadow-lg hover:shadow-brand-500/10"
                 x-show="show" x-transition:enter="transition ease-out duration-700 delay-800" x-transition:enter-start="opacity-0 transform translate-y-10" x-transition:enter-end="opacity-100 transform translate-y-0">
                <button @click="active = (active === 8 ? null : 8)" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                    <span class="text-lg font-semibold text-charcoal-900 group-hover:text-brand-600 transition">Is there a cost to use EventHorizons?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-charcoal-500 transition-transform duration-300" :class="active === 8 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 8" x-collapse x-cloak class="px-8 pb-6 text-charcoal-600 leading-relaxed border-t border-charcoal-200 pt-4">
                    Joining EventHorizons and RSVPing to free events is completely free. Some exclusive events may have ticket tiers with specific pricing set by the organizer. Creating and hosting events is currently open to all members!
                </div>
            </div>
        </div>
    </div>
</div>
