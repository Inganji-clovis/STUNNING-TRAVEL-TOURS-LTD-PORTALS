import { prisma } from "@/lib/prisma";
import Image from "next/image";
import Link from "next/link";
import { 
  UserPlus, 
  Search, 
  Filter, 
  MoreVertical, 
  Edit, 
  Trash2, 
  ShieldCheck,
  UserX
} from "lucide-react";

export default async function UsersPage() {
  const users = await prisma.user.findMany({
    orderBy: { created_at: 'desc' }
  });

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase">Staff Directory</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1">Manage personnel and permissions</p>
        </div>
        <Link 
          href="/admin/users/create" 
          className="flex items-center gap-3 px-8 py-4 bg-red-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-red-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all"
        >
          <UserPlus size={16} />
          Register Personnel
        </Link>
      </div>

      {/* Filter Bar */}
      <div className="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div className="relative flex-1 w-full">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input 
            type="text" 
            placeholder="Search by name, email or username..." 
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-red-600 transition-all font-bold text-sm"
          />
        </div>
        <div className="flex gap-2 w-full md:w-auto">
          <button className="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-3 bg-slate-50 text-slate-600 border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-widest">
            <Filter size={14} /> Filter
          </button>
        </div>
      </div>

      {/* Users Table */}
      <div className="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden whitespace-nowrap">
        <div className="overflow-x-auto custom-scrollbar">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50 border-b border-slate-100">
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Staff Member</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Access Role</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Verification</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {users.map((user) => (
                <tr key={user.id} className="hover:bg-slate-50/50 transition-colors group">
                  <td className="px-8 py-6">
                    <div className="flex items-center gap-4">
                      <div className="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 font-black text-xs shadow-sm overflow-hidden">
                         {user.name[0]}
                      </div>
                      <div>
                        <p className="font-black text-slate-900 text-sm tracking-tight">{user.name}</p>
                        <p className="text-[10px] font-bold text-slate-400 tracking-widest uppercase">@{user.username || 'n/a'}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center gap-2">
                      <span className={`px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border ${
                        user.role === 'admin' ? 'bg-red-50 text-red-600 border-red-100' :
                        user.role === 'md' ? 'bg-purple-50 text-purple-600 border-purple-100' :
                        user.role === 'sales' ? 'bg-blue-50 text-blue-600 border-blue-100' :
                        'bg-slate-50 text-slate-600 border-slate-100'
                      }`}>
                        {user.role}
                      </span>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center gap-2">
                       <div className={`w-2 h-2 rounded-full ${user.is_active ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-slate-300'}`} />
                       <span className="text-[11px] font-black text-slate-900 uppercase tracking-widest">
                         {user.is_active ? 'Active' : 'Inactive'}
                       </span>
                    </div>
                  </td>
                  <td className="px-8 py-6 text-center">
                     {user.email_verified_at ? (
                       <ShieldCheck className="mx-auto text-emerald-500" size={20} />
                     ) : (
                       <span className="text-[10px] font-black text-slate-300 uppercase italic">Pending</span>
                     )}
                  </td>
                  <td className="px-8 py-6 text-right">
                     <div className="flex items-center justify-end gap-2">
                        <button className="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                          <Edit size={16} />
                        </button>
                        <button className="p-2.5 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                          <UserX size={16} />
                        </button>
                     </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
