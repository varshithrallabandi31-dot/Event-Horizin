<?php
// Generate event schedule based on event type and start time
$eventStartTime = strtotime($event['start_time']);
$eventType = strtolower($event['category'] ?? 'social');

// Define schedules for different event types
$schedules = [
    'music' => [
        ['time' => '-30 minutes', 'title' => 'Arrival & Refreshments'],
        ['time' => '0 minutes', 'title' => 'Welcome & Opening Ritual'],
        ['time' => '+30 minutes', 'title' => 'Main Performance Begins'],
        ['time' => '+90 minutes', 'title' => 'Intermission & Networking'],
        ['time' => '+120 minutes', 'title' => 'Second Set'],
        ['time' => '+180 minutes', 'title' => 'Closing & Photo Moments'],
        ['time' => '+210 minutes', 'title' => 'Event Ends']
    ],
    'tech' => [
        ['time' => '-15 minutes', 'title' => 'Registration & Networking'],
        ['time' => '0 minutes', 'title' => 'Opening Keynote'],
        ['time' => '+45 minutes', 'title' => 'Panel Discussion'],
        ['time' => '+90 minutes', 'title' => 'Break & Refreshments'],
        ['time' => '+105 minutes', 'title' => 'Workshop Sessions'],
        ['time' => '+165 minutes', 'title' => 'Q&A & Networking'],
        ['time' => '+195 minutes', 'title' => 'Closing Remarks']
    ],
    'art' => [
        ['time' => '-20 minutes', 'title' => 'Gallery Opening'],
        ['time' => '0 minutes', 'title' => 'Welcome & Artist Introduction'],
        ['time' => '+30 minutes', 'title' => 'Guided Exhibition Tour'],
        ['time' => '+75 minutes', 'title' => 'Interactive Workshop'],
        ['time' => '+120 minutes', 'title' => 'Artist Meet & Greet'],
        ['time' => '+150 minutes', 'title' => 'Closing Reception']
    ],
    'social' => [
        ['time' => '-30 minutes', 'title' => 'Arrival & Refreshments'],
        ['time' => '0 minutes', 'title' => 'Welcome & Opening Ritual'],
        ['time' => '+30 minutes', 'title' => 'Guided Reflection Activities'],
        ['time' => '+75 minutes', 'title' => 'Sharing Circle'],
        ['time' => '+120 minutes', 'title' => 'Setting Intentions'],
        ['time' => '+150 minutes', 'title' => 'Closing & Photo Moments'],
        ['time' => '+180 minutes', 'title' => 'Event Ends']
    ]
];

$eventSchedule = $schedules[$eventType] ?? $schedules['social'];
?>

<!-- Countdown Section (Full Screen) -->
<div class="relative h-screen w-full flex items-center justify-center overflow-hidden" id="countdown-section">
    <!-- Background Image -->
    <img src="<?php echo $event['image_url'] ?? 'https://via.placeholder.com/1920x1080'; ?>" 
         class="absolute inset-0 w-full h-full object-cover">
    
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70"></div>
    
    <!-- Countdown Content -->
    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
        <span class="inline-block py-2 px-4 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white text-sm font-bold mb-6">
            <?php echo htmlspecialchars($event['category'] ?? ''); ?>
        </span>
        
        <h1 class="serif-heading text-5xl md:text-7xl font-bold text-white mb-8 leading-tight">
            <?php echo htmlspecialchars($event['title'] ?? ''); ?>
        </h1>
        
        <p class="text-xl text-white/90 mb-12 font-light">
            <?php echo date('l, F j, Y • g:i A', strtotime($event['start_time'])); ?>
        </p>
        
        <!-- Countdown Timer -->
        <div class="flex gap-6 justify-center mb-12" x-data="countdown('<?php echo date('Y-m-d H:i:s', strtotime($event['start_time'])); ?>')">
            <div class="text-center">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 min-w-[100px]">
                    <h3 class="text-5xl font-bold text-white" x-text="days">00</h3>
                    <p class="text-sm text-white/70 uppercase mt-2 font-semibold">Days</p>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 min-w-[100px]">
                    <h3 class="text-5xl font-bold text-white" x-text="hours">00</h3>
                    <p class="text-sm text-white/70 uppercase mt-2 font-semibold">Hours</p>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 min-w-[100px]">
                    <h3 class="text-5xl font-bold text-white" x-text="minutes">00</h3>
                    <p class="text-sm text-white/70 uppercase mt-2 font-semibold">Minutes</p>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 min-w-[100px]">
                    <h3 class="text-5xl font-bold text-white" x-text="seconds">00</h3>
                    <p class="text-sm text-white/70 uppercase mt-2 font-semibold">Seconds</p>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="animate-bounce">
            <i data-lucide="chevron-down" class="w-8 h-8 text-white/70"></i>
        </div>
    </div>
</div>

<!-- Event Content Section (Light Theme) -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="eventPage()">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <!-- Left Column: Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Tab Navigation -->
            <div class="flex items-center gap-2 border-b-2 border-charcoal-100 mb-8 sticky top-0 bg-gray-50/80 backdrop-blur-md z-20 py-4 overflow-x-auto no-scrollbar">
                <?php 
                $tabs = ['About', 'Schedule', 'Venue', 'FAQ'];
                $isOrganizer = (isset($_SESSION['user_id']) && $event['organizer_id'] == $_SESSION['user_id']);
                if ($rsvpStatus === 'approved' || $isOrganizer) {
                    $tabs[] = 'Memories';
                    $tabs[] = 'Community';
                    $tabs[] = 'Refer';
                }
                ?>
                <?php foreach($tabs as $tab): ?>
                    <button @click="selectedTab = '<?php echo strtolower($tab); ?>'; if('<?php echo strtolower($tab); ?>' === 'venue') window.dispatchEvent(new CustomEvent('show-map'))" 
                        :class="selectedTab === '<?php echo strtolower($tab); ?>' ? 'bg-brand-500 text-white shadow-lg' : 'text-charcoal-500 hover:bg-gray-100'"
                        class="px-6 py-2 rounded-xl font-bold transition flex items-center gap-2 whitespace-nowrap">
                        <?php echo $tab; ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Tab Content -->
            
            <!-- About Tab -->
            <div x-show="selectedTab === 'about'" x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-95" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-white rounded-3xl p-8 border-2 border-charcoal-200 shadow-sm">
                    <h2 class="serif-heading text-3xl font-bold text-charcoal-900 mb-6">About this event</h2>
                    <div class="text-charcoal-700 leading-relaxed space-y-4">
                        <?php echo nl2br(htmlspecialchars($event['description'] ?? '')); ?>
                    </div>
                </div>
            </div>

            <!-- FAQ Tab -->
            <div x-show="selectedTab === 'faq'" x-transition>
                <div class="bg-white rounded-3xl p-8 border-2 border-charcoal-200 shadow-sm">
                    <h2 class="serif-heading text-3xl font-bold text-charcoal-900 mb-6">Frequently Asked Questions</h2>
                    <div class="space-y-4">
                        <?php if (!empty($faqs)): ?>
                            <?php foreach($faqs as $faq): ?>
                                <div x-data="{ expanded: false }" class="border-2 border-charcoal-100 rounded-2xl overflow-hidden transition hover:border-brand-300">
                                    <button @click="expanded = !expanded" class="w-full px-6 py-4 flex items-center justify-between text-left bg-cream-50 hover:bg-white transition">
                                        <span class="font-bold text-charcoal-800 text-lg"><?php echo htmlspecialchars($faq['question']); ?></span>
                                        <i :class="expanded ? 'rotate-180' : ''" data-lucide="chevron-down" class="w-5 h-5 text-brand-500 transition-transform duration-300"></i>
                                    </button>
                                    <div x-show="expanded" x-collapse class="bg-white px-6 py-4 text-charcoal-600 leading-relaxed border-t border-charcoal-100">
                                        <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-12 opacity-60">
                                <i data-lucide="help-circle" class="w-12 h-12 mx-auto mb-3 text-charcoal-300"></i>
                                <p class="text-charcoal-500">No FAQs added yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Schedule Tab -->
            <div x-show="selectedTab === 'schedule'" x-transition>
                <div class="bg-white rounded-3xl p-8 border-2 border-charcoal-200 shadow-sm">
                    <h2 class="serif-heading text-3xl font-bold text-charcoal-900 mb-8">Event Schedule</h2>
                    <div class="relative px-4">
                        <div class="absolute left-[29px] top-0 bottom-0 w-0.5 bg-brand-200"></div>
                        <div class="space-y-8">
                            <?php foreach($eventSchedule as $item): 
                                $itemTime = strtotime($item['time'], $eventStartTime);
                            ?>
                            <div class="relative flex items-start gap-8">
                                <div class="w-8 h-8 rounded-full bg-brand-500 border-4 border-white shadow-md flex-shrink-0 z-10 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                                <div>
                                    <p class="text-brand-600 font-bold text-sm mb-1 uppercase tracking-wider"><?php echo date('g:i A', $itemTime); ?></p>
                                    <p class="text-charcoal-900 font-bold text-xl"><?php echo htmlspecialchars($item['title']); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Venue Tab -->
            <div x-show="selectedTab === 'venue'" x-transition @show-map.window="setTimeout(() => initMap(), 100)">
                <div class="bg-white rounded-3xl overflow-hidden border-2 border-charcoal-200 shadow-sm">
                    <!-- Leaflet Map Container -->
                    <div id="venueMap" class="h-80 bg-gray-100 relative z-10 grayscale contrast-125"></div>
                    
                    <div class="p-8 relative">
                        <!-- Floating Location Card (matches reference style) -->
                        <div class="absolute -top-16 left-8 bg-white p-4 rounded-xl shadow-xl border-2 border-brand-400 flex items-center gap-4 max-w-xs z-20">
                            <div class="w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-lucide="map-pin" class="text-brand-600 w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="font-bold text-charcoal-900 leading-tight"><?php echo htmlspecialchars($event['location_name'] ?? 'The Venue'); ?></p>
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($event['location_name']); ?>" target="_blank" class="text-[10px] text-brand-600 font-bold hover:underline uppercase tracking-widest mt-1 inline-block">Get Directions</a>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h2 class="serif-heading text-3xl font-bold text-charcoal-900 mb-4">The Venue</h2>
                            <p class="text-charcoal-600 mb-6"><?php echo htmlspecialchars($event['location_name']); ?></p>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-cream-50 p-4 rounded-2xl border-2 border-charcoal-100">
                                    <p class="text-[10px] text-charcoal-400 uppercase font-bold tracking-widest mb-1">Access</p>
                                    <p class="text-sm font-bold text-charcoal-800">Wheelchair Friendly</p>
                                </div>
                                <div class="bg-cream-50 p-4 rounded-2xl border-2 border-charcoal-100">
                                    <p class="text-[10px] text-charcoal-400 uppercase font-bold tracking-widest mb-1">Parking</p>
                                    <p class="text-sm font-bold text-charcoal-800">Street Parking Available</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Memories Tab -->
            <div x-show="selectedTab === 'memories'" x-transition>
                <div class="bg-white rounded-3xl p-8 border-2 border-charcoal-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="serif-heading text-3xl font-bold text-charcoal-900">Memories</h2>
                        <span class="bg-brand-100 text-brand-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">Gallery</span>
                    </div>
                    <p class="text-charcoal-600 mb-8 italic border-l-4 border-brand-400 pl-4 py-1">
                        "Created memories at event? Please share with us"
                    </p>

                    <!-- Submission Form -->
                    <form action="<?php echo BASE_URL; ?>event/<?php echo $event['id']; ?>/memories" method="POST" class="mb-10 bg-cream-50 p-6 rounded-2xl border-2 border-dashed border-charcoal-300">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-charcoal-700 uppercase tracking-wider mb-2">Image URL</label>
                                <div class="flex gap-2">
                                    <div class="bg-white p-3 rounded-xl border-2 border-charcoal-200 flex items-center justify-center text-charcoal-400">
                                        <i data-lucide="link" class="w-5 h-5"></i>
                                    </div>
                                    <input type="url" name="image_url" required placeholder="https://example.com/photo.jpg" 
                                        class="w-full bg-white border-2 border-charcoal-200 rounded-xl px-4 py-3 text-charcoal-900 focus:outline-none focus:border-brand-500 transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-charcoal-700 uppercase tracking-wider mb-2">Caption (Optional)</label>
                                <input type="text" name="caption" placeholder="Best moment ever!" 
                                    class="w-full bg-white border-2 border-charcoal-200 rounded-xl px-4 py-3 text-charcoal-900 focus:outline-none focus:border-brand-500 transition">
                            </div>
                            <button type="submit" class="w-full bg-charcoal-900 hover:bg-black text-white font-bold py-3 rounded-xl shadow-md transition transform hover:scale-[1.01] flex items-center justify-center gap-2">
                                <i data-lucide="upload-cloud" class="w-5 h-5"></i> Share Memory
                            </button>
                        </div>
                    </form>

                    <!-- Memories Grid -->
                    <?php if (!empty($memories)): ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <?php foreach($memories as $memory): ?>
                                <div class="group relative aspect-square overflow-hidden rounded-2xl bg-gray-100 border-2 border-charcoal-100">
                                    <img src="<?php echo htmlspecialchars($memory['image_url']); ?>" 
                                         class="w-full h-full object-cover transition duration-500 group-hover:scale-110" 
                                         alt="Event memory">
                                    <?php if (!empty($memory['caption'])): ?>
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                            <p class="text-white font-medium text-sm"><?php echo htmlspecialchars($memory['caption']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-10 opacity-50">
                            <i data-lucide="image" class="w-12 h-12 mx-auto mb-2 text-charcoal-300"></i>
                            <p class="text-charcoal-500 text-sm">No memories shared yet. Be the first!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Community Tab -->
            <div x-show="selectedTab === 'community'" x-transition>
                <div class="bg-white rounded-3xl border-2 border-charcoal-200 overflow-hidden shadow-sm">
                    <div class="p-4 border-b-2 border-charcoal-100 flex items-center justify-between bg-cream-50">
                        <h2 class="text-xl font-bold text-charcoal-900 flex items-center gap-2">
                            <i data-lucide="users" class="text-brand-500"></i> Community
                        </h2>
                        <div class="flex bg-white rounded-xl p-1 border-2 border-charcoal-100">
                            <button @click="communityTab = 'chat'" 
                                :class="communityTab === 'chat' ? 'bg-brand-500 text-white shadow-md' : 'text-charcoal-500 hover:bg-gray-100'"
                                class="px-4 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-2">
                                <i data-lucide="message-square" class="w-3 h-3"></i> Chat
                            </button>
                            <button @click="communityTab = 'polls'; fetchPolls()" 
                                :class="communityTab === 'polls' ? 'bg-brand-500 text-white shadow-md' : 'text-charcoal-500 hover:bg-gray-100'"
                                class="px-4 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-2">
                                <i data-lucide="bar-chart-2" class="w-3 h-3"></i> Polls
                            </button>
                        </div>
                    </div>

                    <!-- Chat View -->
                    <div x-show="communityTab === 'chat'" x-transition:enter>
                        <div class="h-96 overflow-y-auto p-6 space-y-4 custom-scrollbar bg-white" id="chat-window">
                            <template x-for="msg in messages">
                                <div class="flex gap-4" :class="msg.user === '<?= $_SESSION['user_name'] ?? '' ?>' ? 'flex-row-reverse' : 'flex-row'">
                                    <div class="flex flex-col" :class="msg.user === '<?= $_SESSION['user_name'] ?? '' ?>' ? 'items-end' : 'items-start'">
                                        <div class="px-4 py-2 rounded-2xl max-w-[90%] shadow-sm" 
                                            :class="msg.user === '<?= $_SESSION['user_name'] ?? '' ?>' ? 'bg-brand-500 text-white rounded-tr-none' : 'bg-gray-100 text-charcoal-800 rounded-tl-none border-2 border-gray-100'">
                                            <p class="text-sm" x-text="msg.content"></p>
                                        </div>
                                        <span class="text-[9px] text-charcoal-400 mt-1 uppercase font-bold tracking-widest" x-text="msg.user + ' • ' + msg.time"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="p-4 bg-gray-50 border-t-2 border-charcoal-100">
                            <div class="flex gap-2">
                                <input type="text" x-model="newMessage" @keyup.enter="sendMessage()" placeholder="Say something..." 
                                    class="flex-grow bg-white border-2 border-charcoal-200 rounded-xl px-4 py-3 text-charcoal-900 text-sm focus:outline-none focus:border-brand-500 transition">
                                <button @click="sendMessage()" class="bg-brand-500 text-white p-3 rounded-xl hover:bg-brand-600 transition shadow-lg shadow-brand-500/20">
                                    <i data-lucide="send" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Polls View -->
                    <div x-show="communityTab === 'polls'" x-transition:enter class="h-[460px] overflow-y-auto p-6 custom-scrollbar bg-white">
                        
                        <?php if($isOrganizer): ?>
                        <div class="mb-8 bg-cream-50 p-6 rounded-2xl border-2 border-dashed border-charcoal-200">
                            <h3 class="font-bold text-charcoal-900 mb-4 text-sm uppercase tracking-wide">Create New Poll</h3>
                            <div class="space-y-3">
                                <input type="text" x-model="newPoll.question" placeholder="Ask a question..." class="w-full bg-white border-2 border-charcoal-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-brand-500">
                                <template x-for="(opt, index) in newPoll.options">
                                    <div class="flex gap-2">
                                        <input type="text" x-model="newPoll.options[index]" :placeholder="'Option ' + (index + 1)" class="w-full bg-white border-2 border-charcoal-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-brand-500">
                                    </div>
                                </template>
                                <button @click="createPoll()" class="w-full bg-charcoal-900 text-white font-bold py-2 rounded-xl text-sm hover:bg-black transition">Post Poll</button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="space-y-6">
                            <template x-for="poll in polls">
                                <div class="border-2 border-charcoal-100 rounded-2xl p-6 bg-white hover:border-brand-200 transition">
                                    <h3 class="font-bold text-xl text-charcoal-900 mb-4" x-text="poll.question"></h3>
                                    
                                    <div class="space-y-3">
                                        <template x-for="option in poll.options">
                                            <div class="relative">
                                                <!-- Voting Button / Result Bar -->
                                                <button @click="votePoll(poll.id, option.id)" 
                                                    :disabled="poll.user_voted_option"
                                                    class="w-full relative overflow-hidden rounded-xl border-2 transition h-12 flex items-center px-4"
                                                    :class="poll.user_voted_option === option.id ? 'border-brand-500 bg-brand-50' : 'border-charcoal-100 hover:border-charcoal-300 bg-white'">
                                                    
                                                    <!-- Progress Bar Background -->
                                                    <div class="absolute left-0 top-0 bottom-0 bg-brand-100 transition-all duration-500"
                                                         :style="'width: ' + (poll.total_votes > 0 ? (option.votes / poll.total_votes * 100) : 0) + '%'"></div>
                                                    
                                                    <!-- Content -->
                                                    <div class="relative z-10 flex justify-between w-full">
                                                        <span class="font-bold text-sm text-charcoal-800" x-text="option.option_text"></span>
                                                        <span class="font-bold text-xs text-brand-600" x-show="poll.total_votes > 0" x-text="Math.round(poll.total_votes > 0 ? (option.votes / poll.total_votes * 100) : 0) + '%'"></span>
                                                    </div>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <p class="text-[10px] text-charcoal-400 font-bold uppercase tracking-widest mt-4 text-right" x-text="poll.total_votes + ' votes'"></p>
                                </div>
                            </template>
                            
                            <div x-show="polls.length === 0" class="text-center py-12 opacity-50">
                                <i data-lucide="bar-chart-2" class="w-12 h-12 mx-auto mb-3 text-charcoal-300"></i>
                                <p class="text-charcoal-500 text-sm">No polls active.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Refer Tab -->
            <div x-show="selectedTab === 'refer'" x-transition>
                <div class="bg-white rounded-3xl p-8 border-2 border-charcoal-200 shadow-sm relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-brand-50 rounded-full opacity-50"></div>
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-brand-100 rounded-2xl flex items-center justify-center text-brand-600">
                                <i data-lucide="user-plus" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h2 class="serif-heading text-3xl font-bold text-charcoal-900">Refer a Friend</h2>
                                <p class="text-charcoal-500 text-sm font-medium">Loved this event? Share this exclusive experience with someone special.</p>
                            </div>
                        </div>

                        <div class="bg-cream-50 p-6 md:p-8 rounded-3xl border-2 border-charcoal-200 max-w-2xl mx-auto shadow-inner">
                            <h3 class="font-bold text-charcoal-800 mb-6 text-center text-lg">Invite your friend to join this event</h3>
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-xs font-bold text-charcoal-700 uppercase tracking-wider mb-2">Friend's Name</label>
                                    <input type="text" x-model="referral.name" placeholder="John Doe" 
                                        class="w-full bg-white border-2 border-charcoal-100 rounded-xl px-4 py-3 text-charcoal-900 focus:outline-none focus:border-brand-500 transition shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-charcoal-700 uppercase tracking-wider mb-2">Friend's Email</label>
                                    <input type="email" x-model="referral.email" placeholder="john@example.com" 
                                        class="w-full bg-white border-2 border-charcoal-100 rounded-xl px-4 py-3 text-charcoal-900 focus:outline-none focus:border-brand-500 transition shadow-sm">
                                </div>
                            </div>
                            <button @click="submitReferral()" :disabled="isReferring"
                                class="w-full bg-charcoal-900 hover:bg-black text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:scale-[1.01] flex items-center justify-center gap-3">
                                <span x-show="!isReferring">Send Formal Invitation</span>
                                <span x-show="isReferring">Sending Invitation...</span>
                                <i data-lucide="send" x-show="!isReferring" class="w-5 h-5"></i>
                            </button>

                            <div x-show="referralSuccess" x-transition class="mt-4 p-4 bg-green-50 border-2 border-green-200 rounded-xl flex items-center gap-3 text-green-700 shadow-sm">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                <span class="font-bold">Formal invitation has been sent to your friend!</span>
                            </div>
                        </div>

                        <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-8">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-12 h-12 bg-cream-100 rounded-full flex items-center justify-center mb-3 border-2 border-charcoal-50">
                                    <i data-lucide="mail" class="w-6 h-6 text-brand-600"></i>
                                </div>
                                <h4 class="font-bold text-charcoal-800 mb-1">Formal Email</h4>
                                <p class="text-xs text-charcoal-500 px-4">We send a beautifully crafted invitation email with all event details.</p>
                            </div>
                            <div class="flex flex-col items-center text-center">
                                <div class="w-12 h-12 bg-cream-100 rounded-full flex items-center justify-center mb-3 border-2 border-charcoal-50">
                                    <i data-lucide="shield-check" class="w-6 h-6 text-brand-600"></i>
                                </div>
                                <h4 class="font-bold text-charcoal-800 mb-1">Verified Reference</h4>
                                <p class="text-xs text-charcoal-500 px-4">Your name is included as a trusted referrer for priority approval.</p>
                            </div>
                            <div class="flex flex-col items-center text-center">
                                <div class="w-12 h-12 bg-cream-100 rounded-full flex items-center justify-center mb-3 border-2 border-charcoal-50">
                                    <i data-lucide="sparkles" class="w-6 h-6 text-brand-600"></i>
                                </div>
                                <h4 class="font-bold text-charcoal-800 mb-1">Join the Circle</h4>
                                <p class="text-xs text-charcoal-500 px-4">Grow our curated community with like-minded individuals.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-8 border-2 border-brand-400 shadow-lg sticky top-24">
                <div class="mb-6">
                    <p class="text-brand-600 font-bold uppercase tracking-widest text-xs mb-1">Date & Time</p>
                    <p class="text-charcoal-900 text-lg font-semibold"><?php echo date('l, M j, Y', strtotime($event['start_time'])); ?></p>
                    <p class="text-charcoal-600 text-sm"><?php echo date('g:i A', strtotime($event['start_time'])); ?></p>
                </div>

                <hr class="border-charcoal-200 mb-6">

                <!-- Dynamic Tiers -->
                <div class="space-y-4 mb-8">
                    <p class="text-charcoal-600 font-bold uppercase tracking-widest text-xs">Ticket Options</p>
                    <?php if (!empty($ticketTiers)): ?>
                        <?php foreach($ticketTiers as $index => $tier): ?>
                            <div class="p-4 rounded-2xl border-2 transition cursor-pointer <?php echo $index === 0 ? 'bg-brand-50 border-brand-400 active-tier' : 'bg-cream-50 border-charcoal-200 hover:border-brand-400'; ?>">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-charcoal-900 font-bold"><?php echo htmlspecialchars($tier['name']); ?></span>
                                    <span class="text-brand-600 font-bold"><?php echo $tier['price'] > 0 ? '$' . number_format($tier['price'], 2) : 'Free'; ?></span>
                                </div>
                                <?php if (!empty($tier['quantity_available'])): ?>
                                    <p class="text-xs text-charcoal-500 italic">Limited spots. (<?php echo $tier['quantity_available']; ?> left)</p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback if no specific tiers defined -->
                        <div class="p-4 bg-brand-50 rounded-2xl border-2 border-brand-400 cursor-pointer hover:border-brand-500 transition active-tier">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-charcoal-900 font-bold">General Admission</span>
                                <span class="text-brand-600 font-bold">Free</span>
                            </div>
                            <p class="text-xs text-charcoal-500 italic">Standard access. Requires host approval.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- RSVP Form/Button -->
                <div x-show="rsvpStatus === null">
                    <div class="mb-4 space-y-4">
                        <!-- Smart Contact Collection -->
                        <div x-show="!hasName">
                            <label class="block text-xs font-semibold text-charcoal-700 mb-2 uppercase tracking-wider">Your Full Name</label>
                            <input type="text" x-model="name" class="w-full bg-cream-50 border-2 border-charcoal-200 rounded-xl px-4 py-2 text-charcoal-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Enter your full name">
                        </div>
                        <div x-show="!hasEmail">
                            <label class="block text-xs font-semibold text-charcoal-700 mb-2 uppercase tracking-wider">Your Email</label>
                            <input type="email" x-model="email" class="w-full bg-cream-50 border-2 border-charcoal-200 rounded-xl px-4 py-2 text-charcoal-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Enter your email">
                        </div>
                        
                        <!-- Logged In As Indicator -->
                        <div x-show="hasName" class="flex items-center gap-2 text-sm text-charcoal-500 bg-charcoal-50 px-3 py-2 rounded-lg">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            <span>Joining as <span class="font-bold text-charcoal-900" x-text="name"></span></span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-charcoal-700 mb-2 uppercase tracking-wider">Why do you want to join?</label>
                            <textarea x-model="interest" rows="2" class="w-full bg-cream-50 border-2 border-charcoal-200 rounded-xl px-4 py-2 text-charcoal-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="I love tech and community..."></textarea>
                        </div>
                    </div>
                    <button @click="submitRSVP()" 
                        :disabled="isSubmitting"
                        class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-brand-500/30 transition transform hover:scale-[1.02] mb-4 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isSubmitting">Request to Join</span>
                        <span x-show="isSubmitting">Sending...</span>
                    </button>
                    <p class="text-center text-[10px] text-charcoal-500 px-4">
                        By requesting, you agree to the host's rules. This event requires <span x-text="requiresApproval ? '**manual approval**' : '**no approval**'"></span> by the organizer.
                    </p>
                </div>

                <div x-show="rsvpStatus === 'pending'" class="text-center py-6 bg-yellow-50 rounded-2xl border-2 border-yellow-400">
                    <i data-lucide="clock" class="text-yellow-600 w-10 h-10 mx-auto mb-3"></i>
                    <h4 class="text-charcoal-900 font-bold mb-1">Request Pending</h4>
                    <p class="text-charcoal-600 text-xs">The organizer will review your request soon.</p>
                </div>

                <div x-show="rsvpStatus === 'approved'" class="space-y-4">
                    <div class="text-center py-6 bg-green-50 rounded-2xl border-2 border-green-400">
                        <i data-lucide="check-circle" class="text-green-600 w-10 h-10 mx-auto mb-3"></i>
                        <h4 class="text-charcoal-900 font-bold mb-1">You're In!</h4>
                        <p class="text-charcoal-600 text-xs">Your request has been approved by the host.</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>event/<?php echo $event['id']; ?>/download-kit" 
                       class="w-full bg-brand-600 text-white hover:bg-brand-700 font-bold py-4 rounded-2xl shadow-lg transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                        <i data-lucide="download" class="w-5 h-5"></i>
                        Download Event Kit
                    </a>
                </div>

                <div x-show="rsvpStatus === 'rejected'" class="text-center py-6 bg-red-50 rounded-2xl border-2 border-red-400">
                    <i data-lucide="x-circle" class="text-red-600 w-10 h-10 mx-auto mb-3"></i>
                    <h4 class="text-charcoal-900 font-bold mb-1">Request Declined</h4>
                    <p class="text-charcoal-600 text-xs">Sorry, the host could not approve your request.</p>
                </div>
            </div>

            <!-- Organizer Info -->
            <div class="bg-white rounded-3xl p-6 border-2 border-charcoal-200 shadow-sm">
                <div class="flex items-center gap-4">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($event['organizer_name'] ?? 'Organizer'); ?>&background=random" class="w-12 h-12 rounded-full border-2 border-charcoal-200">
                    <div>
                        <p class="text-xs text-charcoal-500 mb-0.5">Hosted by</p>
                        <p class="text-charcoal-900 font-bold"><?php echo htmlspecialchars($event['organizer_name'] ?? 'Event Organizer'); ?></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Leaflet Map Dependencies -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let mapInitialized = false;
function initMap() {
    if (mapInitialized) return;
    
    // Default to Bengaluru if coords missing
    const lat = <?php echo !empty($event['latitude']) ? $event['latitude'] : '12.9716'; ?>;
    const lng = <?php echo !empty($event['longitude']) ? $event['longitude'] : '77.5946'; ?>;
    
    const map = L.map('venueMap', {
        zoomControl: false,
        scrollWheelZoom: false,
        dragging: true,
        touchZoom: true
    }).setView([lat, lng], 15);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const customIcon = L.divIcon({
        html: `<div class="w-10 h-10 bg-brand-500 rounded-full border-4 border-white shadow-xl flex items-center justify-center">
                <div class="w-3 h-3 bg-white rounded-full"></div>
               </div>`,
        className: '',
        iconSize: [40, 40],
        iconAnchor: [20, 20]
    });

    L.marker([lat, lng], {icon: customIcon}).addTo(map);
    
    // Invalidate size in case container was hidden
    setTimeout(() => map.invalidateSize(), 200);
    mapInitialized = true;
}

function countdown(eventDate) {
    return {
        days: '00',
        hours: '00',
        minutes: '00',
        seconds: '00',
        
        init() {
            this.updateCountdown();
            setInterval(() => this.updateCountdown(), 1000);
        },
        
        updateCountdown() {
            const now = new Date().getTime();
            const eventTime = new Date(eventDate).getTime();
            const distance = eventTime - now;
            
            if (distance < 0) {
                this.days = '00';
                this.hours = '00';
                this.minutes = '00';
                this.seconds = '00';
                return;
            }
            
            this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
            this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
            this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
            this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
        }
    }
}

function eventPage() {
    return {
        selectedTab: 'about',
        communityTab: 'chat',
        messages: [],
        newMessage: '',
        rsvpStatus: <?php echo json_encode($rsvpStatus); ?>,
        interest: '',
        isSubmitting: false,
        showSuccessModal: false,
        isReferring: false,
        referralSuccess: false,
        referral: {
            name: '',
            email: ''
        },
        name: '<?php echo $currentUser['name'] ?? ''; ?>', 
        email: '<?php echo $currentUser['email'] ?? ''; ?>',
        hasName: <?php echo !empty($currentUser['name']) ? 'true' : 'false'; ?>,
        hasEmail: <?php echo !empty($currentUser['email']) ? 'true' : 'false'; ?>,
        requiresApproval: <?php echo ($event['requires_approval'] ?? 1) == 1 ? 'true' : 'false'; ?>,
        polls: [],
        newPoll: { question: '', options: ['', ''] },
        
        init() {
            // Initial Mock Messages
            this.fetchMessages();
            // Start Polling
            setInterval(() => this.fetchMessages(), 5000);
            lucide.createIcons();
        },

        // ... methods ...

        async submitRSVP() {
            // Validate: If name/email not set (and we don't have them), alert
            if(!this.interest) {
                 alert('Please tell the host why you want to join.');
                 return;
            }
            if (!this.hasName && !this.name) {
                alert('Please provide your name.');
                return;
            }
            if (!this.hasEmail && !this.email) {
                alert('Please provide your email.');
                return;
            }

            this.isSubmitting = true;
            
            try {
                const formData = new FormData();
                formData.append('event_id', <?php echo $event['id']; ?>);
                formData.append('interest', this.interest);
                
                // Only send if we needed to collect it
                if (!this.hasName) formData.append('name', this.name);
                if (!this.hasEmail) formData.append('email', this.email);
                
                const response = await fetch('<?php echo BASE_URL; ?>rsvp/submit', {
                    method: 'POST',
                    body: formData
                });
                
                // Check for redirect (Fetch follows redirects automatically)
                if (response.redirected && response.url.includes('/rsvp/success')) {
                    window.location.href = response.url;
                    return;
                }
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Trigger success modal
                    this.showSuccessModal = true;
                    // Update status in background (modal will handle the rest)
                    this.rsvpStatus = 'pending';
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Something went wrong');
                    if (data.status === 'error' && data.message === 'Already requested') {
                         this.rsvpStatus = 'pending';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                // Fallback: If we can't parse JSON, it might be a successful HTML page load that fetch followed
                // But since we can't access 'response' here easily if valid JSON failed, 
                // typically the check above (response.redirected) handles the success case.
                
                alert('Connection error. Please try again.');
            } finally {
                this.isSubmitting = false;
            }
        },

        submitReferral() {
            if (this.isReferring) return;
            if (!this.referral.name || !this.referral.email) {
                alert('Please fill in both name and email');
                return;
            }

            this.isReferring = true;
            this.referralSuccess = false;

            const formData = new FormData();
            formData.append('friend_name', this.referral.name);
            formData.append('friend_email', this.referral.email);

            fetch(`<?php echo BASE_URL; ?>event/<?php echo $event['id']; ?>/refer`, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    this.referralSuccess = true;
                    this.referral.name = '';
                    this.referral.email = '';
                    setTimeout(() => this.referralSuccess = false, 5000);
                } else {
                    alert(data.message || 'Failed to send referral');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Connection error');
            })
            .finally(() => {
                this.isReferring = false;
            });
        },

        fetchPolls() {
            fetch(`<?php echo BASE_URL; ?>event/<?php echo $event['id']; ?>/polls`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                         this.polls = data.polls;
                    }
                });
        },

        createPoll() {
            console.log('Creating poll...', this.newPoll);
            if (!this.newPoll.question || this.newPoll.options.filter(o => o.trim()).length < 2) {
                alert('Please enter a question and at least 2 options.');
                return;
            }

            const formData = new FormData();
            formData.append('question', this.newPoll.question);
            this.newPoll.options.forEach(opt => formData.append('options[]', opt));

            fetch(`<?php echo BASE_URL; ?>event/<?php echo $event['id']; ?>/create-poll`, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                console.log('Poll creation response:', data);
                if (data.status === 'success') {
                    this.newPoll = { question: '', options: ['', ''] };
                    this.fetchPolls();
                    alert('Poll created successfully!');
                } else {
                    alert(data.message || 'Failed to create poll');
                }
            })
            .catch(err => {
                console.error('Poll creation error:', err);
                alert('Error creating poll. Check console.');
            });
        },

        votePoll(pollId, optionId) {
             const formData = new FormData();
             formData.append('poll_id', pollId);
             formData.append('option_id', optionId);

             fetch(`<?php echo BASE_URL; ?>event/<?php echo $event['id']; ?>/vote-poll`, {
                 method: 'POST',
                 body: formData
             })
             .then(res => res.json())
             .then(data => {
                 if (data.status === 'success') {
                     this.fetchPolls();
                 } else {
                     alert(data.message || 'You have already voted or an error occurred.');
                 }
             });
        }
    }
}
</script>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #9CA3AF; border-radius: 10px; }
.active-tier {
    border-color: #8B7355 !important;
    background: rgba(139, 115, 85, 0.1);
}
</style>

<!-- Success Modal -->
<div x-show="showSuccessModal" style="display: none;" class="fixed inset-0 z-[60] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showSuccessModal = false" x-transition.opacity></div>
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full relative shadow-2xl transform transition-all text-center"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-90 translate-y-4">
        
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
            <i data-lucide="check" class="w-10 h-10 text-green-600"></i>
        </div>
        
        <h3 class="text-2xl font-bold text-charcoal-900 mb-2">Request Sent!</h3>
        <p class="text-charcoal-600 mb-8 leading-relaxed">
            Your request has been sent to the host. You will be notified once it is approved.
        </p>
        
        <button @click="showSuccessModal = false" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-xl shadow-lg transition transform hover:scale-[1.02]">
            Got it, thanks!
        </button>
    </div>
</div>
