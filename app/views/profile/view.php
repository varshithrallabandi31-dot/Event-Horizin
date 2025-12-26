<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ 
    tab: 'history',
    editing: false,
    formData: {
        name: '<?= htmlspecialchars($user['name'] ?? '') ?>',
        bio: '<?= htmlspecialchars($user['bio'] ?? '') ?>',
        interests: '<?= htmlspecialchars($user['interests'] ?? '') ?>'
    }
}">
    <!-- Profile Header -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8 border border-gray-100">
        <div class="h-48 bg-gradient-to-r from-indigo-600 to-purple-600"></div>
        <div class="px-8 pb-8">
            <div class="relative flex justify-between items-end -mt-16 mb-6">
                <div class="relative group">
                    <img src="<?= $user['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&size=128&background=random' ?>" 
                         class="w-32 h-32 rounded-3xl border-4 border-white shadow-lg object-cover bg-white">
                    <label for="avatar-upload" class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <i data-lucide="camera" class="text-white w-8 h-8"></i>
                    </label>
                    <form action="<?= BASE_URL ?>profile/update" method="POST" enctype="multipart/form-data" id="avatar-form" class="hidden">
                        <input type="file" id="avatar-upload" name="avatar" onchange="document.getElementById('avatar-form').submit()">
                    </form>
                </div>
                <button @click="editing = !editing" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    <span x-text="editing ? 'Cancel' : 'Edit Profile'"></span>
                </button>
            </div>

            <div x-show="!editing">
                <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($user['name'] ?? '') ?></h1>
                <p class="text-gray-500 flex items-center gap-2 mt-1">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    <?= htmlspecialchars($user['email'] ?? '') ?>
                </p>
                <?php if (!empty($user['bio'])): ?>
                    <p class="mt-4 text-gray-600 max-w-2xl"><?= nl2br(htmlspecialchars($user['bio'] ?? '')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Edit Form -->
            <form x-show="editing" action="<?= BASE_URL ?>profile/update" method="POST" class="space-y-4 mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" x-model="formData.name" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Interests (comma separated)</label>
                        <input type="text" name="interests" x-model="formData.interests" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                    <textarea name="bio" x-model="formData.bio" rows="3" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-8 border-b border-gray-200 mb-8">
        <button @click="tab = 'history'" :class="tab === 'history' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500'" class="pb-4 px-2 border-b-2 font-semibold transition-all">Event History</button>
        <button @click="tab = 'hosted'" :class="tab === 'hosted' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500'" class="pb-4 px-2 border-b-2 font-semibold transition-all">Hosted Events</button>
    </div>

    <!-- Tab Content: History -->
    <div x-show="tab === 'history'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($participatedEvents)): ?>
            <div class="col-span-full py-12 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <i data-lucide="calendar-x" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                <p class="text-gray-500">You haven't participated in any events yet.</p>
                <a href="<?= BASE_URL ?>explore" class="text-indigo-600 font-semibold mt-2 inline-block">Explore Events</a>
            </div>
        <?php else: ?>
            <?php foreach ($participatedEvents as $event): ?>
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all group">
                    <div class="h-40 relative">
                        <img src="<?= htmlspecialchars($event['image_url']) ?>" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-indigo-600">
                            <?= htmlspecialchars($event['category']) ?>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-2"><?= htmlspecialchars($event['title']) ?></h3>
                        <p class="text-sm text-gray-500 flex items-center gap-2 mb-4">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <?= date('M j, Y', strtotime($event['start_time'])) ?>
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-lg uppercase tracking-wider">Attended</span>
                            <a href="<?= BASE_URL ?>event/<?= $event['id'] ?>" class="text-indigo-600 font-semibold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                                View Details <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Tab Content: Hosted -->
    <div x-show="tab === 'hosted'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($hostedEvents)): ?>
            <div class="col-span-full py-12 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <i data-lucide="plus-circle" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                <p class="text-gray-500">You haven't hosted any events yet.</p>
                <a href="<?= BASE_URL ?>host" class="text-indigo-600 font-semibold mt-2 inline-block">Host Your First Event</a>
            </div>
        <?php else: ?>
            <?php foreach ($hostedEvents as $event): ?>
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all group">
                    <div class="h-40 relative">
                        <img src="<?= htmlspecialchars($event['image_url']) ?>" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-indigo-600 px-3 py-1 rounded-full text-xs font-bold text-white">
                            Organizer
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-2"><?= htmlspecialchars($event['title']) ?></h3>
                        <p class="text-sm text-gray-500 flex items-center gap-2 mb-4">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <?= $event['rsvp_count'] ?? 0 ?> RSVPs
                        </p>
                        <div class="flex justify-between items-center">
                            <a href="<?= BASE_URL ?>organizer/dashboard" class="text-indigo-600 font-semibold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                                Manage Event <i data-lucide="settings" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
