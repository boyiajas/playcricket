
import React, { useState, useEffect } from 'react';
import { User, Role, Member, Campaign, MessageStatus, ChatMessage, Booking, BookingStatus } from './types';
import Sidebar from './components/Sidebar';
import StatCard from './components/StatCard';
import { MOCK_USERS, MOCK_CAMPAIGNS, MOCK_MEMBERS, MOCK_CHATS, MOCK_AUDIT_LOGS, MOCK_BOOKINGS } from './services/mockData';
import { generateTemplate, analyzeSentiment } from './services/geminiService';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts';

// ----- HELPERS -----

const CalendarGrid = ({ year, month, bookings, onDateClick }: { year: number, month: number, bookings: Booking[], onDateClick: (date: string) => void }) => {
  const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const firstDay = new Date(year, month, 1).getDay(); // 0 = Sun

  const gridCells = [];
  for (let i = 0; i < firstDay; i++) {
    gridCells.push(<div key={`empty-${i}`} className="bg-slate-50/50 h-20 md:h-32 border-r border-b border-slate-100"></div>);
  }
  
  for (let d = 1; d <= daysInMonth; d++) {
    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
    const dayBookings = bookings.filter(b => b.date === dateStr);
    const isToday = year === 2023 && month === 9 && d === 28; 

    gridCells.push(
      <div 
        key={d} 
        onClick={() => onDateClick(dateStr)}
        className={`h-20 md:h-32 border-r border-b border-slate-100 p-1 md:p-2 hover:bg-emerald-50/30 transition-colors cursor-pointer relative group ${isToday ? 'bg-blue-50/50' : 'bg-white'}`}
      >
        <div className="flex justify-between items-start">
           <span className={`text-xs md:text-sm font-semibold w-6 h-6 md:w-7 md:h-7 flex items-center justify-center rounded-full ${isToday ? 'bg-blue-600 text-white shadow-md' : 'text-slate-700'}`}>{d}</span>
           <button className="hidden md:flex opacity-0 group-hover:opacity-100 text-emerald-600 bg-emerald-100 hover:bg-emerald-200 w-5 h-5 md:w-6 md:h-6 rounded-full items-center justify-center text-lg leading-none transition-all shadow-sm transform hover:scale-110">
             +
           </button>
        </div>
        <div className="mt-1 space-y-1 overflow-y-auto max-h-[calc(100%-1.5rem)] scrollbar-hide">
          {dayBookings.map(b => (
            <div key={b.id} className={`text-[8px] md:text-[10px] px-1 md:px-1.5 py-0.5 md:py-1 rounded border truncate shadow-sm ${
              b.status === 'CONFIRMED' 
                ? 'bg-emerald-100 text-emerald-800 border-emerald-200' 
                : 'bg-orange-50 text-orange-800 border-orange-200'
            }`}>
              <span className="font-bold hidden md:inline">{b.startTime} </span> {b.facility}
            </div>
          ))}
        </div>
      </div>
    );
  }

  return (
    <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
      <div className="grid grid-cols-7 border-b border-slate-200 bg-slate-50">
        {days.map(day => (
          <div key={day} className="py-2 text-center text-[10px] md:text-xs font-bold text-slate-500 uppercase tracking-wider">
            {day}
          </div>
        ))}
      </div>
      <div className="grid grid-cols-7 bg-slate-100 gap-px border-l border-t border-slate-200">
        {gridCells}
      </div>
    </div>
  );
};

// ----- SUB-PAGES -----

const Dashboard = ({ bookings, campaigns }: { bookings: Booking[], campaigns: Campaign[] }) => {
  const channelData = [
    { name: 'WhatsApp', sent: 1500, response: 900 },
    { name: 'Email', sent: 800, response: 200 },
    { name: 'SMS', sent: 1200, response: 300 },
  ];
  
  const todayBookings = bookings.filter(b => b.date === '2023-10-28').length;
  const activeMembers = MOCK_MEMBERS.length;

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h2 className="text-xl md:text-2xl font-bold text-slate-800">Ground Overview</h2>
        <div className="hidden sm:flex items-center space-x-2 bg-white px-4 py-2 rounded-full shadow-sm">
          <div className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
          <span className="text-sm font-medium text-slate-600">Pitches Active</span>
        </div>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <StatCard title="Today's Bookings" value={todayBookings} icon="🏏" color="bg-emerald-500" trend="Full" />
        <StatCard title="Active Members" value={activeMembers} icon="👥" color="bg-blue-500" trend="+3" />
        <StatCard title="Broadcasts" value="3,500" icon="📡" color="bg-purple-500" />
        <StatCard title="Revenue" value="$4,200" icon="💰" color="bg-orange-500" trend="15%" />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2 bg-white p-4 md:p-6 rounded-xl shadow-sm border border-slate-100">
          <h3 className="text-lg font-semibold mb-4 text-slate-700">Engagement</h3>
          <div className="h-64 md:h-80">
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={channelData}>
                <CartesianGrid strokeDasharray="3 3" vertical={false} />
                <XAxis dataKey="name" fontSize={12} />
                <YAxis fontSize={12} />
                <Tooltip cursor={{fill: '#f1f5f9'}} contentStyle={{borderRadius: '8px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)'}} />
                <Bar dataKey="sent" fill="#10b981" radius={[4, 4, 0, 0]} name="Sent" />
                <Bar dataKey="response" fill="#3b82f6" radius={[4, 4, 0, 0]} name="Replied" />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>

        <div className="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-slate-100">
          <h3 className="text-lg font-semibold mb-4 text-slate-700">Matches</h3>
          <div className="space-y-3">
             {bookings.slice(0, 4).map(b => {
               const member = MOCK_MEMBERS.find(m => m.id === b.memberId);
               return (
                 <div key={b.id} className="flex items-center justify-between p-3 hover:bg-slate-50 rounded-lg transition-colors border border-slate-100">
                   <div className="max-w-[60%]">
                     <p className="font-medium text-slate-800 text-sm truncate">{b.facility}</p>
                     <p className="text-[10px] text-slate-500">{b.date} • {b.startTime}</p>
                   </div>
                   <div className="text-right">
                      <span className="text-[10px] font-bold text-slate-700 block truncate">{member?.name}</span>
                      <span className={`text-[8px] uppercase font-bold ${b.status === 'CONFIRMED' ? 'text-emerald-600' : 'text-orange-500'}`}>{b.status}</span>
                   </div>
                 </div>
               )
             })}
          </div>
          <button className="w-full mt-4 text-center text-sm text-emerald-600 font-medium hover:underline">View All</button>
        </div>
      </div>
    </div>
  );
};

const BookingSystem = () => {
  const [activeView, setActiveView] = useState<'calendar' | 'my-bookings'>('calendar');
  const [bookings, setBookings] = useState<Booking[]>(MOCK_BOOKINGS);
  const [showModal, setShowModal] = useState(false);
  const [currentDate, setCurrentDate] = useState({ year: 2023, month: 9 }); 
  
  const [newBooking, setNewBooking] = useState({
    date: '',
    time: '10:00',
    facility: 'Pitch 1',
    duration: 1
  });

  const handleDateClick = (date: string) => {
    setNewBooking(prev => ({ ...prev, date }));
    setShowModal(true);
  };

  const handleBook = () => {
    const booking: Booking = {
      id: Date.now().toString(),
      memberId: 'm1',
      facility: newBooking.facility as any,
      date: newBooking.date,
      startTime: newBooking.time,
      durationHours: newBooking.duration,
      status: BookingStatus.CONFIRMED,
      syncedToGoogle: false
    };
    setBookings([...bookings, booking]);
    setShowModal(false);
  };

  const myBookings = bookings.filter(b => b.memberId === 'm1');

  return (
    <div className="space-y-4 md:space-y-6">
      <div className="flex flex-col xl:flex-row justify-between items-center gap-4">
        <div className="flex w-full md:w-auto bg-white p-1 rounded-lg border border-slate-200 shadow-sm overflow-x-auto scrollbar-hide">
          <button 
            onClick={() => setActiveView('calendar')}
            className={`flex-1 md:flex-none px-3 md:px-4 py-2 rounded-md font-medium text-xs md:text-sm transition-colors flex items-center justify-center space-x-2 whitespace-nowrap ${activeView === 'calendar' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'}`}
          >
            <span>📅</span>
            <span>Calendar</span>
          </button>
          <button 
            onClick={() => setActiveView('my-bookings')}
            className={`flex-1 md:flex-none px-3 md:px-4 py-2 rounded-md font-medium text-xs md:text-sm transition-colors flex items-center justify-center space-x-2 whitespace-nowrap ${activeView === 'my-bookings' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'}`}
          >
            <span>📋</span>
            <span>Schedule</span>
          </button>
        </div>
        
        <div className="flex w-full md:w-auto items-center justify-between md:justify-end space-x-3">
            {activeView === 'calendar' && (
                <div className="flex items-center bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm">
                    <button onClick={() => setCurrentDate(prev => prev.month === 0 ? { year: prev.year - 1, month: 11 } : { ...prev, month: prev.month - 1 })} className="text-slate-400 hover:text-emerald-600">◀</button>
                    <span className="mx-2 md:mx-4 font-bold text-slate-700 text-xs md:text-sm whitespace-nowrap">October 2023</span>
                    <button onClick={() => setCurrentDate(prev => prev.month === 11 ? { year: prev.year + 1, month: 0 } : { ...prev, month: prev.month + 1 })} className="text-slate-400 hover:text-emerald-600">▶</button>
                </div>
            )}
            <button 
            onClick={() => {
                setNewBooking(prev => ({ ...prev, date: new Date().toISOString().split('T')[0] }));
                setShowModal(true);
            }}
            className="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 text-xs md:text-sm font-medium flex items-center shadow-lg transition-all"
            >
            <span>+ Book</span>
            </button>
        </div>
      </div>

      {showModal && (
        <div className="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-[100] p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl animate-in fade-in zoom-in duration-200">
            <h3 className="text-xl font-bold mb-4 text-slate-800 flex items-center">
                <span className="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-2 text-sm">🏏</span>
                Book a Slot
            </h3>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-1">Facility</label>
                <select 
                  className="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                  value={newBooking.facility}
                  onChange={e => setNewBooking({...newBooking, facility: e.target.value})}
                >
                  <option>Pitch 1 (Turf)</option>
                  <option>Pitch 2 (Matting)</option>
                  <option>Indoor Nets</option>
                  <option>Bowling Machine</option>
                </select>
              </div>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-slate-700 mb-1">Date</label>
                  <input 
                    type="date" 
                    className="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                    value={newBooking.date}
                    onChange={e => setNewBooking({...newBooking, date: e.target.value})}
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium text-slate-700 mb-1">Time</label>
                  <input 
                    type="time" 
                    className="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                    value={newBooking.time}
                    onChange={e => setNewBooking({...newBooking, time: e.target.value})}
                  />
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-1">Duration (Hours)</label>
                <input 
                  type="number" 
                  min="1"
                  max="8"
                  className="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                  value={newBooking.duration}
                  onChange={e => setNewBooking({...newBooking, duration: parseInt(e.target.value)})}
                />
              </div>
            </div>
            <div className="mt-6 flex justify-end space-x-3 pt-4 border-t border-slate-100">
              <button onClick={() => setShowModal(false)} className="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Cancel</button>
              <button onClick={handleBook} className="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 shadow-lg transition-all">Confirm</button>
            </div>
          </div>
        </div>
      )}

      {activeView === 'calendar' ? (
        <CalendarGrid 
          year={currentDate.year} 
          month={currentDate.month} 
          bookings={bookings} 
          onDateClick={handleDateClick} 
        />
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            {myBookings.length === 0 ? (
                <div className="col-span-full py-12 text-center text-slate-400 border-2 border-dashed border-slate-200 rounded-xl">
                    <span className="text-4xl block mb-2">📅</span>
                    <p>No upcoming bookings found.</p>
                </div>
            ) : myBookings.map(booking => {
            const member = MOCK_MEMBERS.find(m => m.id === booking.memberId);
            return (
                <div key={booking.id} className="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-slate-200 hover:border-emerald-300 transition-colors relative overflow-hidden group">
                    <div className="absolute top-0 right-0 p-3">
                    <span className={`px-2 py-1 text-[8px] md:text-[10px] font-bold rounded uppercase ${
                        booking.status === 'CONFIRMED' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700'
                    }`}>
                        {booking.status}
                    </span>
                    </div>
                    
                    <h4 className="text-base md:text-lg font-bold text-slate-800 mb-1">{booking.facility}</h4>
                    <p className="text-xs text-slate-500 mb-4">
                        🗓 {booking.date} | ⏰ {booking.startTime}
                    </p>
                    
                    <div className="flex items-center mb-4">
                    <div className="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600 mr-2">
                        {member?.name.charAt(0)}
                    </div>
                    <div>
                        <p className="text-xs font-medium text-slate-700">{member?.name}</p>
                        <p className="text-[10px] text-slate-400">{member?.membershipType}</p>
                    </div>
                    </div>

                    <div className="flex items-center space-x-2 border-t pt-3 md:pt-4">
                      <button className="flex-1 text-[10px] font-medium py-1.5 rounded border border-slate-200 text-slate-600 hover:bg-slate-50">Sync</button>
                      <button className="flex-1 text-[10px] font-medium py-1.5 rounded border border-slate-200 text-slate-600 hover:bg-red-50 hover:text-red-600">Cancel</button>
                    </div>
                </div>
            );
            })}
        </div>
      )}
    </div>
  );
};

const MemberList = () => (
  <div className="space-y-4 md:space-y-6">
    <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 className="text-xl md:text-2xl font-bold text-slate-800">Members</h2>
        <div className="flex space-x-2 w-full sm:w-auto">
          <button className="flex-1 sm:flex-none px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs md:text-sm font-medium">CSV</button>
          <button className="flex-1 sm:flex-none px-4 py-2 bg-emerald-600 text-white rounded-lg text-xs md:text-sm font-medium">+ Add</button>
        </div>
    </div>
    <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
        <table className="w-full text-left min-w-[600px]">
          <thead className="bg-slate-50 border-b border-slate-200">
            <tr>
              <th className="px-4 md:px-6 py-4 text-[10px] md:text-xs font-semibold text-slate-500 uppercase">Member</th>
              <th className="px-4 md:px-6 py-4 text-[10px] md:text-xs font-semibold text-slate-500 uppercase">Membership</th>
              <th className="px-4 md:px-6 py-4 text-[10px] md:text-xs font-semibold text-slate-500 uppercase">Tags</th>
              <th className="px-4 md:px-6 py-4 text-[10px] md:text-xs font-semibold text-slate-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-100">
            {MOCK_MEMBERS.map((m) => (
              <tr key={m.id} className="hover:bg-slate-50">
                <td className="px-4 md:px-6 py-4">
                  <p className="font-medium text-slate-800 text-sm">{m.name}</p>
                  <p className="text-[10px] text-slate-500">{m.email}</p>
                </td>
                <td className="px-4 md:px-6 py-4">
                  <span className={`px-2 py-0.5 rounded-full text-[10px] font-semibold ${m.membershipType === 'Club Pro' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-600'}`}>
                    {m.membershipType}
                  </span>
                </td>
                <td className="px-4 md:px-6 py-4">
                  <div className="flex flex-wrap gap-1">
                    {m.tags.slice(0, 2).map(t => <span key={t} className="px-2 py-0.5 bg-green-50 text-green-700 border border-green-100 text-[10px] rounded">{t}</span>)}
                  </div>
                </td>
                <td className="px-4 md:px-6 py-4 text-[10px] md:text-sm">
                  <button className="text-emerald-600 hover:text-emerald-800 font-medium mr-2 md:mr-3">Msg</button>
                  <button className="text-slate-400 hover:text-slate-600">Edit</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
    </div>
  </div>
);

const ChatInterface = () => {
  const [selectedMember, setSelectedMember] = useState<string | null>(null);
  const [inputText, setInputText] = useState('');
  const [chats, setChats] = useState<ChatMessage[]>(MOCK_CHATS);

  const handleSend = () => {
    if (!inputText.trim()) return;
    const newMsg: ChatMessage = {
      id: Date.now().toString(),
      memberId: selectedMember || 'm1',
      sender: 'AGENT',
      content: inputText,
      timestamp: new Date().toISOString()
    };
    setChats([...chats, newMsg]);
    setInputText('');
  };

  return (
    <div className="h-[calc(100vh-10rem)] md:h-[calc(100vh-8rem)] flex bg-white rounded-xl overflow-hidden shadow-sm border border-slate-200">
      {/* Member List */}
      <div className={`w-full md:w-1/3 border-r border-slate-200 flex-col ${selectedMember ? 'hidden md:flex' : 'flex'}`}>
        <div className="p-4 border-b border-slate-200 bg-slate-50">
          <h3 className="font-bold text-slate-700 text-sm">Member Support</h3>
          <input type="text" placeholder="Search..." className="mt-2 w-full px-3 py-1.5 border border-slate-300 rounded-lg text-xs" />
        </div>
        <div className="flex-1 overflow-y-auto">
          {MOCK_MEMBERS.map(member => (
            <div 
              key={member.id}
              onClick={() => setSelectedMember(member.id)}
              className={`p-4 border-b border-slate-100 hover:bg-emerald-50 cursor-pointer transition-colors ${selectedMember === member.id ? 'bg-emerald-50 border-l-4 border-l-emerald-500' : ''}`}
            >
              <div className="flex justify-between items-start">
                <h4 className="font-semibold text-slate-800 text-sm">{member.name}</h4>
                <span className="text-[10px] text-slate-400">Now</span>
              </div>
              <p className="text-xs text-slate-500 truncate mt-1">Last message preview...</p>
            </div>
          ))}
        </div>
      </div>

      {/* Chat Area */}
      <div className={`flex-1 flex-col bg-slate-50 ${!selectedMember ? 'hidden md:flex' : 'flex'}`}>
        {selectedMember ? (
          <>
            <div className="p-3 bg-white border-b border-slate-200 flex justify-between items-center shadow-sm">
              <div className="flex items-center space-x-3">
                <button onClick={() => setSelectedMember(null)} className="md:hidden text-slate-400 mr-1 text-lg">←</button>
                <div className="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold text-xs">
                  {MOCK_MEMBERS.find(c => c.id === selectedMember)?.name.charAt(0)}
                </div>
                <div>
                  <h3 className="font-bold text-slate-800 text-xs md:text-sm">{MOCK_MEMBERS.find(c => c.id === selectedMember)?.name}</h3>
                  <p className="text-[10px] text-slate-500">Online</p>
                </div>
              </div>
            </div>

            <div className="flex-1 overflow-y-auto p-4 space-y-4">
              {chats.filter(c => c.memberId === selectedMember || (!selectedMember && c.memberId === 'm1')).map((msg) => (
                <div key={msg.id} className={`flex ${msg.sender === 'MEMBER' ? 'justify-start' : 'justify-end'}`}>
                  <div className={`max-w-[85%] md:max-w-[70%] p-3 rounded-xl shadow-sm ${
                    msg.sender === 'MEMBER' 
                      ? 'bg-white text-slate-800 rounded-tl-none border border-slate-100' 
                      : msg.sender === 'SYSTEM'
                      ? 'bg-yellow-50 text-yellow-800 text-[10px] border border-yellow-100 mx-auto text-center'
                      : 'bg-emerald-600 text-white rounded-tr-none'
                  }`}>
                    <p className="text-xs">{msg.content}</p>
                    <p className={`text-[8px] mt-1 ${msg.sender === 'MEMBER' ? 'text-slate-400' : 'text-emerald-200'}`}>
                      {new Date(msg.timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                    </p>
                  </div>
                </div>
              ))}
            </div>

            <div className="p-3 bg-white border-t border-slate-200">
              <div className="flex space-x-2">
                <input 
                  type="text" 
                  value={inputText}
                  onChange={(e) => setInputText(e.target.value)}
                  onKeyPress={(e) => e.key === 'Enter' && handleSend()}
                  placeholder="Message..." 
                  className="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-all text-sm"
                />
                <button 
                  onClick={handleSend}
                  className="px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-sm"
                >
                  ➤
                </button>
              </div>
            </div>
          </>
        ) : (
          <div className="flex-1 flex flex-col items-center justify-center text-slate-400">
            <span className="text-5xl mb-4">💬</span>
            <p className="text-sm">Select a member to chat</p>
          </div>
        )}
      </div>
    </div>
  );
};

const CampaignManager = () => {
  const [showCreate, setShowCreate] = useState(false);
  const [topic, setTopic] = useState('');
  const [generatedContent, setGeneratedContent] = useState('');
  const [loadingAi, setLoadingAi] = useState(false);
  
  const handleGenerate = async () => {
    setLoadingAi(true);
    const content = await generateTemplate(topic, 'WhatsApp', 'Exciting and Sports-focused');
    if (content) setGeneratedContent(content);
    setLoadingAi(false);
  };

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 className="text-xl md:text-2xl font-bold text-slate-800">Broadcasts</h2>
        <button 
          onClick={() => setShowCreate(!showCreate)}
          className="w-full sm:w-auto px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium text-xs md:text-sm"
        >
          <span>+ New Announcement</span>
        </button>
      </div>

      {showCreate && (
        <div className="bg-white p-4 md:p-6 rounded-xl shadow-md border border-slate-100 animate-in slide-in-from-top-4 duration-300">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div className="space-y-4">
              <div>
                <label className="block text-xs font-medium text-slate-700 mb-1">Campaign Name</label>
                <input type="text" className="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm" placeholder="e.g., Trial Matches" />
              </div>
              <div>
                <label className="block text-xs font-medium text-slate-700 mb-1">Topic for AI</label>
                <textarea 
                  className="w-full px-3 py-2 border rounded-lg h-24 focus:ring-2 focus:ring-emerald-500 outline-none resize-none text-sm"
                  placeholder=" renovation next week..."
                  value={topic}
                  onChange={(e) => setTopic(e.target.value)}
                />
                <button 
                  onClick={handleGenerate}
                  disabled={loadingAi}
                  className="mt-2 text-[10px] md:text-xs text-purple-600 font-medium hover:text-purple-700"
                >
                  {loadingAi ? 'Drafting...' : '✨ Generate with Gemini'}
                </button>
              </div>
            </div>
            <div className="bg-slate-50 p-4 rounded-lg border border-slate-200">
              <label className="block text-[10px] font-bold text-slate-500 uppercase mb-2">Preview</label>
              <textarea 
                className="w-full h-full bg-transparent border-none outline-none text-slate-700 font-mono text-xs resize-none min-h-[120px]"
                value={generatedContent}
                onChange={(e) => setGeneratedContent(e.target.value)}
                placeholder="AI content..."
              />
            </div>
          </div>
          <div className="mt-6 flex justify-end space-x-3 pt-4 border-t border-slate-100">
             <button onClick={() => setShowCreate(false)} className="px-4 py-2 text-xs text-slate-600">Cancel</button>
             <button className="px-6 py-2 bg-slate-900 text-white rounded-lg text-xs">Send Blast</button>
          </div>
        </div>
      )}

      <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
        <table className="w-full text-left min-w-[500px]">
          <thead className="bg-slate-50 border-b border-slate-200">
            <tr>
              <th className="px-4 md:px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">Campaign</th>
              <th className="px-4 md:px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">Status</th>
              <th className="px-4 md:px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">Stats</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-100">
            {MOCK_CAMPAIGNS.map((c) => (
              <tr key={c.id} className="hover:bg-slate-50">
                <td className="px-4 md:px-6 py-4">
                  <p className="font-medium text-slate-800 text-sm">{c.name}</p>
                  <p className="text-[10px] text-slate-400">{c.channel}</p>
                </td>
                <td className="px-4 md:px-6 py-4">
                   <div className="flex items-center space-x-2">
                     <div className={`w-1.5 h-1.5 rounded-full ${c.status === 'ACTIVE' ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'}`}></div>
                     <span className="text-xs text-slate-600 capitalize">{c.status.toLowerCase()}</span>
                   </div>
                </td>
                <td className="px-4 md:px-6 py-4 text-[10px] text-slate-600">
                  <span className="text-blue-600 font-bold">{c.stats.sent}</span> Sent
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

const AuditTrail = () => (
  <div className="space-y-6">
    <h2 className="text-xl md:text-2xl font-bold text-slate-800">Audit Trail</h2>
    <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
      <table className="w-full text-left min-w-[600px]">
        <thead className="bg-slate-50 border-b border-slate-200">
          <tr>
            <th className="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">Timestamp</th>
            <th className="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">User</th>
            <th className="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">Action</th>
          </tr>
        </thead>
        <tbody className="divide-y divide-slate-100">
          {MOCK_AUDIT_LOGS.map((log) => (
            <tr key={log.id} className="hover:bg-slate-50">
              <td className="px-6 py-4 text-xs text-slate-500">{new Date(log.timestamp).toLocaleString([], {month:'short', day:'numeric', hour:'2-digit', minute:'2-digit'})}</td>
              <td className="px-6 py-4 text-xs font-medium text-slate-800">{log.userId}</td>
              <td className="px-6 py-4 text-xs text-slate-600">{log.details}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  </div>
);

const Login = ({ onLogin }: { onLogin: (role: Role) => void }) => {
  return (
    <div className="min-h-screen bg-slate-900 flex items-center justify-center p-4">
      <div className="bg-white w-full max-w-md rounded-2xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
        <div className="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
        <div className="text-center mb-8">
          <h1 className="text-2xl md:text-3xl font-bold text-slate-900">PlayCricket</h1>
          <p className="text-sm text-slate-500">Facility Management Login</p>
        </div>
        
        <div className="space-y-4">
          <div>
            <label className="block text-xs font-medium text-slate-700 mb-1">Email</label>
            <input type="email" className="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm" placeholder="coach@playcricket.com" />
          </div>
          <div>
            <label className="block text-xs font-medium text-slate-700 mb-1">Password</label>
            <input type="password" className="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm" placeholder="••••••••" />
          </div>
          
          <div className="bg-slate-50 p-4 rounded-lg border border-slate-200 text-center">
            <p className="text-[10px] text-slate-500 mb-2 uppercase font-bold tracking-wider">Simulate Role</p>
            <div className="grid grid-cols-2 gap-2">
              <button onClick={() => onLogin(Role.SUPER_ADMIN)} className="px-3 py-1.5 bg-purple-600 text-white text-[10px] rounded hover:bg-purple-700 font-bold uppercase">Admin</button>
              <button onClick={() => onLogin(Role.MANAGER)} className="px-3 py-1.5 bg-blue-600 text-white text-[10px] rounded hover:bg-blue-700 font-bold uppercase">Manager</button>
              <button onClick={() => onLogin(Role.STAFF)} className="px-3 py-1.5 bg-emerald-600 text-white text-[10px] rounded hover:bg-emerald-700 font-bold uppercase">Staff</button>
              <button onClick={() => onLogin(Role.MEMBER)} className="px-3 py-1.5 bg-orange-500 text-white text-[10px] rounded hover:bg-orange-600 font-bold uppercase">Member</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

const App: React.FC = () => {
  const [currentUser, setCurrentUser] = useState<User | null>(null);
  const [activeTab, setActiveTab] = useState('dashboard');
  const [isSidebarOpen, setIsSidebarOpen] = useState(true);

  // Auto-collapse sidebar on small screens
  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth < 1024) {
        setIsSidebarOpen(false);
      } else {
        setIsSidebarOpen(true);
      }
    };
    handleResize(); // Initial check
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const handleLogin = (role: Role) => {
    const user = MOCK_USERS.find(u => u.role === role) || MOCK_USERS[0];
    setCurrentUser(user);
    setActiveTab(role === Role.MEMBER ? 'bookings' : 'dashboard');
  };

  const handleLogout = () => {
    setCurrentUser(null);
  };

  if (!currentUser) {
    return <Login onLogin={handleLogin} />;
  }

  return (
    <div className="flex bg-slate-50 min-h-screen font-sans text-slate-900 overflow-x-hidden">
      <Sidebar 
        currentTab={activeTab} 
        setTab={setActiveTab} 
        userRole={currentUser.role} 
        onLogout={handleLogout} 
        isOpen={isSidebarOpen}
        setIsOpen={setIsSidebarOpen}
      />
      
      {/* Dynamic margin based on sidebar state */}
      <main className={`flex-1 transition-all duration-300 ease-in-out ${isSidebarOpen ? 'md:ml-64' : 'md:ml-20'} p-4 md:p-8`}>
        {/* Mobile / Responsive Header Top Bar */}
        <header className="flex justify-between items-center mb-6 md:mb-8">
          <div className="flex items-center space-x-3">
            <button 
              onClick={() => setIsSidebarOpen(!isSidebarOpen)}
              className="p-2 bg-white rounded-lg border border-slate-200 shadow-sm hover:bg-slate-50 transition-colors"
              aria-label="Toggle Sidebar"
            >
              <span className="text-xl">{isSidebarOpen ? '⇠' : '☰'}</span>
            </button>
            <div className="hidden sm:block">
              <h1 className="text-xl md:text-2xl font-bold text-slate-800 capitalize leading-tight">
                {activeTab.replace('-', ' ')}
              </h1>
              <p className="text-xs text-slate-500">Welcome, {currentUser.name}</p>
            </div>
          </div>
          
          <div className="flex items-center space-x-3 md:space-x-4">
             <div className="text-right hidden md:block">
               <p className="text-sm font-bold text-slate-800">{currentUser.name}</p>
               <p className="text-[10px] text-slate-500 uppercase font-bold tracking-tight">{currentUser.role}</p>
             </div>
             <div className="w-10 h-10 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-sm shrink-0">
                <img src={currentUser.avatarUrl} alt="User" className="w-full h-full object-cover" />
             </div>
          </div>
        </header>

        {/* Content Area */}
        <div className="max-w-7xl mx-auto">
          {activeTab === 'dashboard' && <Dashboard bookings={MOCK_BOOKINGS} campaigns={MOCK_CAMPAIGNS} />}
          {activeTab === 'bookings' && <BookingSystem />}
          {activeTab === 'members' && <MemberList />}
          {activeTab === 'campaigns' && <CampaignManager />}
          {activeTab === 'chat' && <ChatInterface />}
          {activeTab === 'audit' && currentUser.role === Role.SUPER_ADMIN && <AuditTrail />}
          
          {(activeTab === 'reports' || activeTab === 'settings') && (
            <div className="flex flex-col items-center justify-center h-64 md:h-96 border-2 border-dashed border-slate-300 rounded-xl text-slate-400">
              <span className="text-4xl mb-2">🚧</span>
              <p className="font-medium text-sm md:text-base">Settings & Reports Module</p>
              <p className="text-[10px] md:text-xs">Configure Twilio & Mailgun API keys here.</p>
            </div>
          )}
        </div>
      </main>
    </div>
  );
};

export default App;
