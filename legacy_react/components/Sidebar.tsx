
import React from 'react';
import { Role } from '../types';

interface SidebarProps {
  currentTab: string;
  setTab: (tab: string) => void;
  userRole: Role;
  onLogout: () => void;
  isOpen: boolean;
  setIsOpen: (open: boolean) => void;
}

const Sidebar: React.FC<SidebarProps> = ({ currentTab, setTab, userRole, onLogout, isOpen, setIsOpen }) => {
  const menuItems = [
    { id: 'dashboard', label: 'Dashboard', icon: '📊' },
    { id: 'bookings', label: 'Bookings', icon: '🏏' },
    { id: 'members', label: 'Members', icon: '👥' },
    { id: 'campaigns', label: 'Broadcasts', icon: '📢' },
    { id: 'chat', label: 'Live Chat', icon: '💬' },
    { id: 'reports', label: 'Reports', icon: '📈' },
  ];

  if (userRole === Role.SUPER_ADMIN) {
    menuItems.push({ id: 'audit', label: 'Audit Trail', icon: '🛡️' });
    menuItems.push({ id: 'settings', label: 'Settings', icon: '⚙️' });
  }

  return (
    <>
      {/* Mobile Backdrop Overlay */}
      <div 
        className={`fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 transition-opacity duration-300 md:hidden ${isOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'}`}
        onClick={() => setIsOpen(false)}
      ></div>

      {/* Sidebar Container */}
      <aside 
        className={`fixed left-0 top-0 h-screen bg-slate-900 text-white z-50 transition-all duration-300 ease-in-out flex flex-col overflow-y-auto
          ${isOpen ? 'w-64 translate-x-0' : 'w-0 -translate-x-full md:translate-x-0 md:w-20'}
        `}
      >
        {/* Logo Section */}
        <div className={`p-6 border-b border-slate-700 flex items-center ${!isOpen ? 'justify-center' : 'justify-between'}`}>
          {isOpen ? (
            <div>
              <h1 className="text-xl font-bold bg-gradient-to-r from-emerald-400 to-green-500 text-transparent bg-clip-text whitespace-nowrap">
                CricBook Pro
              </h1>
              <p className="text-[10px] text-slate-400 mt-0.5">Facility Manager</p>
            </div>
          ) : (
            <div className="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center font-bold text-lg">CP</div>
          )}
          
          {/* Close button for mobile */}
          <button 
            onClick={() => setIsOpen(false)}
            className="md:hidden text-slate-400 hover:text-white"
          >
            ✕
          </button>
        </div>

        {/* Navigation */}
        <nav className="flex-1 p-4 space-y-2">
          {menuItems.map((item) => (
            <button
              key={item.id}
              onClick={() => {
                setTab(item.id);
                if (window.innerWidth < 768) setIsOpen(false);
              }}
              className={`w-full flex items-center px-4 py-3 rounded-lg transition-all duration-200 group relative
                ${currentTab === item.id
                  ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50'
                  : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                }
                ${!isOpen ? 'justify-center' : 'space-x-3'}
              `}
              title={item.label}
            >
              <span className="text-xl">{item.icon}</span>
              {isOpen && <span className="font-medium whitespace-nowrap">{item.label}</span>}
              {!isOpen && (
                <span className="absolute left-full ml-4 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-50">
                  {item.label}
                </span>
              )}
            </button>
          ))}
        </nav>

        {/* Bottom Actions */}
        <div className="p-4 border-t border-slate-700">
          <button
            onClick={onLogout}
            className={`w-full flex items-center py-2 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-lg transition-colors
              ${!isOpen ? 'justify-center' : 'px-4 space-x-3'}
            `}
          >
            <span>🚪</span>
            {isOpen && <span>Sign Out</span>}
          </button>
        </div>
      </aside>
    </>
  );
};

export default Sidebar;
