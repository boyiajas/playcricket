import React from 'react';

interface StatCardProps {
  title: string;
  value: string | number;
  trend?: string;
  icon: string;
  color: string;
}

const StatCard: React.FC<StatCardProps> = ({ title, value, trend, icon, color }) => {
  return (
    <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-start justify-between">
      <div>
        <p className="text-sm font-medium text-slate-500 mb-1">{title}</p>
        <h3 className="text-2xl font-bold text-slate-800">{value}</h3>
        {trend && (
          <p className="text-xs font-medium text-emerald-600 mt-2 flex items-center">
            <span>↑</span>
            <span className="ml-1">{trend} this week</span>
          </p>
        )}
      </div>
      <div className={`p-3 rounded-lg ${color} bg-opacity-10`}>
        <span className="text-2xl">{icon}</span>
      </div>
    </div>
  );
};

export default StatCard;
