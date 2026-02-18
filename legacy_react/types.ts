export enum Role {
  SUPER_ADMIN = 'SUPER_ADMIN',
  MANAGER = 'MANAGER',
  STAFF = 'STAFF',
  MEMBER = 'MEMBER'
}

export enum Channel {
  WHATSAPP = 'WHATSAPP',
  EMAIL = 'EMAIL',
  SMS = 'SMS'
}

export enum MessageStatus {
  PENDING = 'PENDING',
  SENT = 'SENT',
  DELIVERED = 'DELIVERED',
  READ = 'READ',
  FAILED = 'FAILED'
}

export enum BookingStatus {
  CONFIRMED = 'CONFIRMED',
  PENDING = 'PENDING',
  CANCELLED = 'CANCELLED',
  COMPLETED = 'COMPLETED'
}

export interface User {
  id: string;
  name: string;
  email: string;
  role: Role;
  department: string; // e.g., "Coaching", "Groundskeeping", "Member"
  avatarUrl: string;
}

export interface Member {
  id: string;
  name: string;
  phone: string;
  email: string;
  membershipType: 'Standard' | 'Premium' | 'Club Pro';
  assignedStaffId?: string;
  tags: string[]; // e.g., "Bowler", "Batsman", "U-19"
}

export interface Booking {
  id: string;
  memberId: string;
  facility: 'Pitch 1' | 'Pitch 2' | 'Indoor Nets' | 'Bowling Machine';
  date: string;
  startTime: string; // e.g., "14:00"
  durationHours: number;
  status: BookingStatus;
  syncedToGoogle: boolean;
}

export interface Campaign {
  id: string;
  name: string;
  status: 'DRAFT' | 'ACTIVE' | 'COMPLETED';
  channel: Channel;
  createdAt: string;
  stats: {
    total: number;
    sent: number;
    delivered: number;
    failed: number;
    responses: number;
  };
}

export interface ChatMessage {
  id: string;
  memberId: string;
  sender: 'AGENT' | 'MEMBER' | 'SYSTEM';
  content: string;
  timestamp: string;
  isTemplate?: boolean;
}

export interface AuditLog {
  id: string;
  userId: string;
  action: string;
  details: string;
  timestamp: string;
}
