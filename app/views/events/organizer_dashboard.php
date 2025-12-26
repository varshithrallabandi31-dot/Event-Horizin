<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20" x-data="{ selectedEvent: 'all' }">
    <div class="flex items-center justify-between mb-12">
        <h1 class="serif-heading text-4xl font-bold text-charcoal-900">Organizer Dashboard</h1>
        <div class="flex gap-4">
            <button @click="$dispatch('open-mail-modal')" class="px-6 py-2 bg-brand-500 text-white rounded-full font-semibold hover:bg-brand-600 transition flex items-center gap-2">
                <i data-lucide="mail" class="w-4 h-4"></i> Send Mail to All
            </button>
            <button class="px-6 py-2 bg-charcoal-200 text-charcoal-800 rounded-full font-semibold hover:bg-charcoal-300 transition">Export CSV</button>
        </div>
    </div>

    <!-- My Hosted Events Section -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-charcoal-900 mb-6 flex items-center gap-3">
            <i data-lucide="calendar" class="text-brand-500"></i> My Hosted Events
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div @click="selectedEvent = 'all'" 
                 :class="selectedEvent === 'all' ? 'border-brand-500 bg-brand-50' : 'border-charcoal-200 bg-white'"
                 class="p-4 rounded-2xl border-2 cursor-pointer transition hover:border-brand-400 group shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-widest" :class="selectedEvent === 'all' ? 'text-brand-600' : 'text-charcoal-500'">Overview</span>
                    <i data-lucide="layers" class="w-4 h-4" :class="selectedEvent === 'all' ? 'text-brand-500' : 'text-charcoal-400'"></i>
                </div>
                <p class="text-charcoal-900 font-bold">All Requests</p>
                <p class="text-xs text-charcoal-500 mt-1"><?php echo count($rsvps); ?> Total RSVPs</p>
            </div>
            
            <?php foreach($hostedEvents as $event): ?>
            <div @click="selectedEvent = '<?php echo $event['id']; ?>'" 
                 :class="selectedEvent === '<?php echo $event['id']; ?>' ? 'border-brand-500 bg-brand-50' : 'border-charcoal-200 bg-white'"
                 class="p-4 rounded-2xl border-2 cursor-pointer transition hover:border-brand-400 group shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-widest" :class="selectedEvent === '<?php echo $event['id']; ?>' ? 'text-brand-600' : 'text-charcoal-500'"><?php echo htmlspecialchars($event['category'] ?? 'Event'); ?></span>
                        <a href="<?php echo BASE_URL; ?>event/<?php echo $event['id']; ?>" target="_blank" @click.stop class="text-charcoal-400 hover:text-brand-500 transition">
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                        </a>
                    </div>
                    <p class="text-charcoal-900 font-bold truncate"><?php echo htmlspecialchars($event['title'] ?? 'Untitled Event'); ?></p>
                    <p class="text-xs text-charcoal-500 mt-1"><?php echo date('M d', strtotime($event['start_time'])); ?></p>
                </div>
                <div class="mt-4 pt-3 border-t border-charcoal-100 flex justify-between items-center">
                    <span class="text-xs text-charcoal-500"><?php echo $event['rsvp_count']; ?> RSVPs</span>
                    <a href="<?php echo BASE_URL; ?>event/<?php echo $event['id']; ?>/analytics" @click.stop class="text-xs font-bold text-brand-600 hover:text-brand-700 bg-brand-50 px-2 py-1 rounded-md transition hover:bg-brand-100">
                        Analytics
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bulk Mail Modal (Alpine.js) -->
    <div x-data="{ open: false }" @open-mail-modal.window="open = true" x-show="open" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="open = false" class="bg-white w-full max-w-2xl rounded-3xl border-2 border-charcoal-200 shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-charcoal-900">Send Bulk Email</h2>
                    <button @click="open = false" class="text-charcoal-400 hover:text-charcoal-900 transition">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <form action="<?php echo BASE_URL; ?>organizer/send-bulk-mail" method="POST">
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-charcoal-700 mb-2">Subject</label>
                        <input type="text" name="subject" required class="w-full px-4 py-3 bg-cream-50 rounded-xl border-2 border-charcoal-200 text-charcoal-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition" placeholder="Important update about the event">
                    </div>
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-charcoal-700 mb-2">Message</label>
                        <textarea name="message" required rows="6" class="w-full px-4 py-3 bg-cream-50 rounded-xl border-2 border-charcoal-200 text-charcoal-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition" placeholder="Write your message here..."></textarea>
                    </div>
                    <div class="flex justify-end gap-4">
                        <button type="button" @click="open = false" class="px-6 py-3 text-charcoal-600 font-semibold hover:text-brand-600 transition">Cancel</button>
                        <button type="submit" class="px-8 py-3 bg-brand-500 text-white font-bold rounded-xl hover:bg-brand-600 transition transform hover:scale-105">
                            Send to All Candidates
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if(isset($_GET['success'])): ?>
    <div class="mb-8 p-4 bg-green-100 border-2 border-green-400 text-green-700 rounded-2xl">
        <?php if($_GET['success'] === 'mail_sent'): ?>
            <strong>Email Simulated Successfully!</strong><br>
            Since you are on a local server, emails are saved to the <code>public/emails/</code> folder instead of being sent. <a href="<?php echo BASE_URL; ?>public/emails/" target="_blank" class="underline font-bold">View Emails Folder &rarr;</a>
        <?php else: ?>
            Action completed successfully!
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-3xl border-2 border-charcoal-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b-2 border-charcoal-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-charcoal-900">RSVP Requests</h3>
            <div class="text-xs text-charcoal-500" x-text="selectedEvent === 'all' ? 'Showing all requests' : 'Filtered by event'"></div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream-100 text-charcoal-700 text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Candidate</th>
                        <th class="px-6 py-4 font-semibold">Event</th>
                        <th class="px-6 py-4 font-semibold">Interest</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-charcoal-200">
                    <?php foreach($rsvps as $rsvp): ?>
                    <tr class="hover:bg-cream-50 transition" x-show="selectedEvent === 'all' || selectedEvent === '<?php echo $rsvp['event_id']; ?>'">
                        <td class="px-6 py-4">
                            <div class="font-bold text-charcoal-900"><?php echo htmlspecialchars($rsvp['name'] ?? ''); ?></div>
                            <div class="text-charcoal-500 text-sm"><?php echo htmlspecialchars($rsvp['phone'] ?? ''); ?></div>
                        </td>
                        <td class="px-6 py-4 text-charcoal-700">
                            <?php echo htmlspecialchars($rsvp['event_title'] ?? ''); ?>
                        </td>
                        <td class="px-6 py-4 text-charcoal-600 text-sm">
                            <?php 
                                $answers = json_decode($rsvp['answers'], true);
                                echo htmlspecialchars($answers['interest'] ?? 'N/A');
                            ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($rsvp['status'] === 'pending'): ?>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold uppercase">Pending</span>
                            <?php elseif($rsvp['status'] === 'approved'): ?>
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase">Approved</span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold uppercase">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-charcoal-500 text-sm">
                            <?php echo date('M d, Y', strtotime($rsvp['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if($rsvp['status'] === 'pending'): ?>
                                <div class="flex justify-end gap-2">
                                    <a href="<?php echo BASE_URL; ?>rsvp/<?php echo $rsvp['id']; ?>/approve" class="p-2 bg-green-100 text-green-700 hover:bg-green-600 hover:text-white rounded-lg transition">
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>rsvp/<?php echo $rsvp['id']; ?>/reject" class="p-2 bg-red-100 text-red-700 hover:bg-red-600 hover:text-white rounded-lg transition">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </a>
                                </div>
                            <?php else: ?>
                                <span class="text-charcoal-400 text-sm">No actions</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
