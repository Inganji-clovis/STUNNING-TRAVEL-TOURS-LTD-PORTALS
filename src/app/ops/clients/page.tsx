import { prisma } from "@/lib/prisma";
import Image from "next/image";
import Link from "next/link";
import { 
  Plus, 
  Search, 
  Filter, 
  MoreVertical, 
  Edit, 
  CheckCircle2, 
  User,
  Building2,
  Mail,
  History,
  Phone,
  ShieldCheck,
  Hash,
  Star
} from "lucide-react";
import ClientForm from "@/components/ops/ClientForm";

export default async function ClientDatabasePage() {
  const [clients, organizations] = await Promise.all([
    prisma.client.findMany({
      orderBy: { created_at: 'desc' },
      include: { organization: true, registrar: true }
    }),
    prisma.organization.findMany({
      select: { id: true, name: true },
      orderBy: { name: 'asc' }
    })
  ]);

  return (
    <div className="space-y-8 font-outfit uppercase font-black">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Client Database</h1>
          <p className="text-slate-400 font-bold text-[10px] tracking-[0.2em] uppercase mt-1 italic">Integrated CRM & Operational Archive</p>
        </div>
        <ClientForm organizations={organizations} />
      </div>

      {/* Filter & Search Bar */}
      <div className="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col md:flex-row gap-6 items-center italic">
        <div className="relative flex-1 w-full">
          <Search className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input 
            type="text" 
            placeholder="Search by Name, TIN or Email..." 
            className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:outline-none focus:border-emerald-500 transition-all" 
          />
        </div>
        <div className="flex gap-4 w-full md:w-auto">
           <button className="flex-1 md:flex-none flex items-center justify-center gap-3 px-8 py-4 bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-100 rounded-2xl hover:bg-slate-100 transition-all">
              <Filter size={16} /> Filters
           </button>
        </div>
      </div>

      {/* Client Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        {clients.length > 0 ? clients.map((client) => (
          <div key={client.id} className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden flex flex-col justify-between min-h-[420px] italic">
            <div>
               <div className="flex justify-between items-start mb-6">
                  <div className={`w-14 h-14 rounded-2xl flex items-center justify-center border shadow-sm ${
                    client.membership_type === 'platinum' ? 'bg-indigo-50 border-indigo-100 text-indigo-600' :
                    client.membership_type === 'gold' ? 'bg-amber-50 border-amber-100 text-amber-600' :
                    'bg-emerald-50 border-emerald-100 text-emerald-600'
                  }`}>
                    <User size={28} />
                  </div>
                  <div className="text-right">
                    <div className="flex items-center gap-2 justify-end mb-1">
                       <Star size={10} className={client.membership_type !== 'standard' ? 'text-amber-500' : 'text-slate-200'} fill="currentColor" />
                       <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{client.membership_type} tier</p>
                    </div>
                    <p className="text-[10px] font-bold text-slate-900 tracking-widest">ID: {client.id.toString().padStart(4, '0')}</p>
                  </div>
               </div>

               <div className="space-y-6">
                  <div>
                    <h3 className="text-xl font-black text-slate-900 uppercase tracking-tight truncate pr-4 italic leading-none">{client.name}</h3>
                    {client.organization && (
                      <div className="flex items-center gap-2 mt-3 p-2 bg-slate-50 rounded-xl border border-slate-100 inline-flex max-w-full overflow-hidden">
                        <Building2 size={12} className="text-blue-500 shrink-0" />
                        <span className="text-[9px] font-black text-blue-600 uppercase tracking-widest truncate">{client.organization.name}</span>
                      </div>
                    )}
                  </div>

                  <div className="grid grid-cols-1 gap-3 pt-2">
                    <div className="flex items-center gap-4 p-3 bg-slate-50/50 rounded-xl border border-slate-100 shadow-inner">
                       <Mail size={14} className="text-slate-400" />
                       <span className="text-[10px] font-bold text-slate-600 lowercase tracking-tight truncate">{client.email || 'NO_ENCRYPT_EMAIL'}</span>
                    </div>
                    <div className="flex items-center gap-4 p-3 bg-slate-50/50 rounded-xl border border-slate-100 shadow-inner">
                       <Phone size={14} className="text-slate-400" />
                       <span className="text-[10px] font-bold text-slate-600 tracking-widest">{client.phone || 'NO_COMMS_DATA'}</span>
                    </div>
                  </div>

                  {client.tin && (
                    <div className="flex items-center gap-2 px-3 py-1 bg-yellow-50 text-yellow-700 rounded-lg border border-yellow-100 self-start inline-flex">
                       <Hash size={10} />
                       <span className="text-[9px] font-black uppercase tracking-widest">TIN: {client.tin}</span>
                    </div>
                  )}
               </div>
            </div>

            <div className="flex items-center justify-between pt-8 mt-8 border-t border-slate-50">
               <div className="flex items-center gap-2">
                  <div className="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-500 uppercase border border-slate-200">
                    {client.registrar.name[0]}
                  </div>
                  <div>
                    <span className="block text-[8px] font-black text-slate-400 uppercase tracking-widest italic leading-none">Registered By</span>
                    <span className="block text-[10px] font-bold text-slate-700 mt-1">{client.registrar.name}</span>
                  </div>
               </div>
               <div className="flex gap-2">
                  <button className="p-3 bg-slate-50 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-100 rounded-2xl transition-all shadow-sm">
                    <Edit size={18} />
                  </button>
                  <Link href={`/ops/clients/${client.id}`} className="p-3 bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 border border-slate-100 hover:border-blue-100 rounded-2xl transition-all shadow-sm">
                    <History size={18} />
                  </Link>
               </div>
            </div>
          </div>
        )) : (
          <div className="col-span-full py-40 bg-white rounded-[2.5rem] border-2 border-slate-100 border-dashed text-center flex flex-col items-center gap-6 shadow-inner">
             <div className="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 shadow-inner">
                <ShieldCheck size={56} />
             </div>
             <div>
                <p className="text-slate-900 font-black text-2xl uppercase tracking-tighter italic">Client Registry Empty</p>
                <p className="text-slate-400 font-bold text-xs uppercase tracking-[0.3em] mt-2 italic shadow-sm">Initiate registration protocol to populate database</p>
             </div>
          </div>
        )}
      </div>
    </div>
  );
}
