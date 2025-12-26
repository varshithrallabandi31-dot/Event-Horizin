# EventHorizon - Future of Event Management

EventHorizon is a cutting-edge event management platform designed to create immersive, community-driven experiences. It combines a premium "Glassmorphism" UI with robust features for organizers and attendees.

## 🌟 Key Features

### 📅 Event Management
*   **Create Event Wizard**: Multi-step, intuitive wizard for creating events with auto-saving drafts.
*   **Ticket Tiers**: Support for multiple ticket types (Free, VIP, Early Bird) with custom pricing.
*   **Approval Settings**:
    *   *Auto-Approval*: Guests join instantly.
    *   *Manual Approval*: Organizers review and approve guests.
*   **Rich Details**: Deep integration of location (Latitude/Longitude), categories, and cover images.

### 🎟️ RSVP & Ticketing
*   **Smart RSVP System**:
    *   Collects Name/Email only once (smart profile updates).
    *   Auto-fills details for returning users.
*   **Instant Digital Kits**:
    *   Upon approval, users receive a PDF "Digital Kit" via email.
    *   Kits include: Entry Pass (QR Code), Ritual Guide, Certificates, and Dynamic Content (e.g., Seasonal Charts).
*   **Success Page**: Verified "You're In" animation and confirmation.

### 📊 Host Analytics
*   **Real-time Insights**: Visual charts showing RSVP trends over the last 7 days.
*   **Attendance Tracking**: Monitor approval status (Pending vs Approved).
*   **Demographics**: (Planned) See breakdown of attendee interests.

### 💬 Community & Engagement
*   **Event Chat**: Private, real-time chat room for every event (Host + Attendees).
*   **Memories Gallery**: Post-event shared photo gallery for attendees to upload moments.
*   **Polls**: Hosts can create live polls to gather attendee feedback.
*   **FAQs**: Built-in FAQ section for each event to reduce support queries.
*   **Referral System**: "Invite a Friend" feature to grow the community organically.

### 👤 User Experience
*   **Profile Management**: Update rudimentary details (Name, Bio, Interests).
*   **My Tickets**: Centralized view of all upcoming and past events.
*   **Modern UI**: Sleek, responsive design using TailwindCSS with a "Glassmorphism" aesthetic (translucency, blurs).
*   **Email Notifications**: Assessment-style emails with PDF attachments using SMTP.

## 🛠️ Technology Stack
*   **Backend**: PHP (MVC Architecture, No Framework)
*   **Frontend**: TailwindCSS, Alpine.js (for interactivity), Lucide Icons
*   **Database**: MySQL
*   **PDF Engine**: FPDF (Customized for transparency/alpha support)
*   **Design**: Glassmorphism / Dark & Light Mode Support

## 🚀 Setup & Installation
1.  **Clone Repository**: `git clone [repo-url]`
2.  **Database**: Import `schema.sql` (if available) or create tables: `users`, `events`, `rsvps`, `ticket_tiers`, `memories`, `messages`, `polls`.
3.  **Config**: Update `app/config/database.php` with your MySQL credentials.
4.  **Mail**: Update `app/libs/MailHelper.php` with your SMTP/Gmail credentials.
5.  **Run**: Host on XAMPP/Apache and navigate to `localhost/Event-Horizin`.
