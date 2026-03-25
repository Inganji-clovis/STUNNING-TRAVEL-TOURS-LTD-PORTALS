import { prisma } from "@/lib/prisma";
import { 
  UserPlus, 
  ArrowLeft, 
  ShieldCheck, 
  Mail, 
  User, 
  Lock, 
  Phone,
  Briefcase,
  Save,
  Info
} from "lucide-react";
import Link from "next/link";
import { registerStaff } from "@/lib/admin-actions";

export default async function RegisterPersonnelPage() {
  return (
    <div className="max-w-4xl mx-auto space-y-8 pb-20 italic">
      {/* Header */}
      <div className="flex items-center gap-6">
        <Link href="/admin/users" className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all shadow-sm">
           <ArrowLeft size={20} />
        </Link>
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic underline decoration-red-100 decoration-8 underline-offset-8">Staff Provisioning</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-3 italic">Administrative Personnel Registration</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Form Column */}
        <div className="lg:col-span-2">
           <form action={registerStaff} className="bg-white rounded-[3rem] p-10 border border-slate-200 shadow-sm space-y-8">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                 <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                    <div className="relative">
                       <User className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                       <input 
                         name="name"
                         type="text" 
                         required
                         placeholder="John Doe"
                         className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-red-600 transition-all uppercase placeholder:text-slate-300 italic"
                       />
                    </div>
                 </div>

                 <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Digital Username</label>
                    <div className="relative">
                       <ShieldCheck className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                       <input 
                         name="username"
                         type="text" 
                         required
                         placeholder="jdoe"
                         className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-red-600 transition-all lowercase placeholder:text-slate-300 italic"
                       />
                    </div>
                 </div>

                 <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Corporate Email</label>
                    <div className="relative">
                       <Mail className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                       <input 
                         name="email"
                         type="email" 
                         required
                         placeholder="john@stunningtravel.com"
                         className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-red-600 transition-all lowercase placeholder:text-slate-300 italic"
                       />
                    </div>
                 </div>

                 <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mobile Contact</label>
                    <div className="relative">
                       <Phone className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                       <input 
                         name="phone"
                         type="text" 
                         placeholder="+250 788 ..."
                         className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-red-600 transition-all italic"
                       />
                    </div>
                 </div>

                 <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Access Protocol (Role)</label>
                    <div className="relative">
                       <Briefcase className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                       <select name="role" required className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-red-600 transition-all uppercase italic">
                          <option value="operations">Operations Unit</option>
                          <option value="sales">Sales & Finance</option>
                          <option value="md">MD Executive</option>
                          <option value="admin">Global Admin</option>
                       </select>
                    </div>
                 </div>

                 <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Initial Password</label>
                    <div className="relative">
                       <Lock className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                       <input 
                         name="password"
                         type="password" 
                         required
                         placeholder="••••••••"
                         className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-red-600 transition-all italic"
                       />
                    </div>
                 </div>
              </div>

              <div className="pt-8 border-t border-slate-100 flex items-center justify-between">
                 <div className="flex items-center gap-3">
                    <ShieldCheck size={20} className="text-emerald-500" />
                    <span className="text-[10px] font-black uppercase text-slate-400 tracking-widest">Digital Identity Verified</span>
                 </div>
                 <button className="px-12 py-5 bg-red-600 text-white rounded-[1.5rem] font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl shadow-red-900/20 active:scale-95">
                    <UserPlus size={18} /> Register Personnel
                 </button>
              </div>
           </form>
        </div>

        {/* Info Column */}
        <div className="space-y-8">
           <div className="bg-slate-900 p-8 rounded-[3rem] text-white italic shadow-2xl relative overflow-hidden">
              <div className="relative z-10">
                 <div className="flex items-center gap-4 mb-8">
                    <Info size={24} className="text-red-500" />
                    <h3 className="text-sm font-black uppercase tracking-widest">HQ SECURITY ADVISORY</h3>
                 </div>
                 <p className="text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em] leading-relaxed mb-8">
                    Staff provisioning grants immediate access to proprietary HQ portals. Ensure all credentials follow the corporate security baseline.
                 </p>
                 <div className="space-y-4">
                    <div className="flex items-center gap-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                       <span className="w-1.5 h-1.5 rounded-full bg-red-500" />
                       Roles strictly define access
                    </div>
                    <div className="flex items-center gap-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                       <span className="w-1.5 h-1.5 rounded-full bg-red-500" />
                       Activity is logged globally
                    </div>
                 </div>
              </div>
              <div className="absolute top-0 right-0 p-8 opacity-10">
                 <ShieldCheck size={180} />
              </div>
           </div>

           <div className="bg-white p-8 rounded-[2.5rem] border border-slate-200">
               <h4 className="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-6 border-b border-slate-50 pb-4">Role Privileges</h4>
               <div className="space-y-4">
                  {[
                    { role: 'MD', desc: 'Full Visibility & Strategy' },
                    { role: 'SALES', desc: 'Financials & Invoicing' },
                    { role: 'OPS', desc: 'Ticketing & Passports' },
                    { role: 'ADMIN', desc: 'Cloud Hub & Users' }
                  ].map((item, i) => (
                    <div key={i} className="flex justify-between items-center text-[10px] uppercase font-black">
                       <span className="text-slate-900">{item.role}</span>
                       <span className="text-slate-400 italic">{item.desc}</span>
                    </div>
                  ))}
               </div>
           </div>
        </div>
      </div>
    </div>
  );
}
