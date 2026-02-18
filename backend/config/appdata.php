<?php

return [
    'users' => [
        ['id' => 'u1', 'name' => 'Coach Carter', 'email' => 'admin@cricbook.com', 'role' => 'SUPER_ADMIN', 'department' => 'Management', 'avatarUrl' => 'https://picsum.photos/200'],
        ['id' => 'u2', 'name' => 'Groundsman Gary', 'email' => 'gary@cricbook.com', 'role' => 'MANAGER', 'department' => 'Facilities', 'avatarUrl' => 'https://picsum.photos/201'],
        ['id' => 'u3', 'name' => 'Sarah Staff', 'email' => 'sarah@cricbook.com', 'role' => 'STAFF', 'department' => 'Front Desk', 'avatarUrl' => 'https://picsum.photos/202'],
        ['id' => 'u4', 'name' => 'Rahul Dravid (Member)', 'email' => 'rahul@cricket.com', 'role' => 'MEMBER', 'department' => 'Member', 'avatarUrl' => 'https://picsum.photos/203'],
    ],
    'members' => [
        ['id' => 'm1', 'name' => 'Virat K.', 'phone' => '+919876543210', 'email' => 'virat@rcb.com', 'membershipType' => 'Club Pro', 'tags' => ['Batsman', 'Captain'], 'assignedStaffId' => 'u3'],
        ['id' => 'm2', 'name' => 'Jasprit B.', 'phone' => '+919123456789', 'email' => 'boom@mi.com', 'membershipType' => 'Premium', 'tags' => ['Bowler', 'Pace'], 'assignedStaffId' => 'u3'],
        ['id' => 'm3', 'name' => 'Local Cricket Club', 'phone' => '+1122334455', 'email' => 'contact@lcc.com', 'membershipType' => 'Standard', 'tags' => ['Team Booking']],
    ],
    'bookings' => [
        ['id' => 'b1', 'memberId' => 'm1', 'facility' => 'Pitch 1', 'date' => '2023-10-28', 'startTime' => '09:00', 'durationHours' => 4, 'status' => 'CONFIRMED', 'syncedToGoogle' => true],
        ['id' => 'b2', 'memberId' => 'm2', 'facility' => 'Indoor Nets', 'date' => '2023-10-28', 'startTime' => '14:00', 'durationHours' => 2, 'status' => 'CONFIRMED', 'syncedToGoogle' => false],
        ['id' => 'b3', 'memberId' => 'm3', 'facility' => 'Pitch 2', 'date' => '2023-10-29', 'startTime' => '10:00', 'durationHours' => 6, 'status' => 'PENDING', 'syncedToGoogle' => false],
    ],
    'campaigns' => [
        ['id' => 'camp1', 'name' => 'Weekend Tournament Registration', 'status' => 'ACTIVE', 'channel' => 'WHATSAPP', 'createdAt' => '2023-10-25', 'stats' => ['total' => 150, 'sent' => 150, 'delivered' => 148, 'failed' => 2, 'responses' => 45]],
        ['id' => 'camp2', 'name' => 'Rain Delay Notification', 'status' => 'COMPLETED', 'channel' => 'SMS', 'createdAt' => '2023-10-27', 'stats' => ['total' => 300, 'sent' => 300, 'delivered' => 290, 'failed' => 10, 'responses' => 5]],
    ],
    'chats' => [
        ['id' => 'msg1', 'memberId' => 'm1', 'sender' => 'SYSTEM', 'content' => 'Reminder: Your slot at Pitch 1 is confirmed for tomorrow.', 'timestamp' => '2023-10-27T08:00:00Z', 'isTemplate' => true],
        ['id' => 'msg2', 'memberId' => 'm1', 'sender' => 'MEMBER', 'content' => 'Can I extend the session by 1 hour?', 'timestamp' => '2023-10-27T08:05:00Z'],
        ['id' => 'msg3', 'memberId' => 'm1', 'sender' => 'AGENT', 'content' => 'Hi Virat, let me check the schedule for you.', 'timestamp' => '2023-10-27T08:06:00Z'],
    ],
    'audit' => [
        ['id' => 'a1', 'userId' => 'u1', 'action' => 'LOGIN', 'details' => 'User logged in', 'timestamp' => '2023-10-27T08:00:00Z'],
        ['id' => 'a2', 'userId' => 'u2', 'action' => 'BOOKING_UPDATE', 'details' => 'Rescheduled Pitch 1 maintenance', 'timestamp' => '2023-10-27T09:30:00Z'],
    ],
];
