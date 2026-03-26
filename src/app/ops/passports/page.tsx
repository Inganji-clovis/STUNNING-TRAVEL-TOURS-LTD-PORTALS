import { prisma } from "@/lib/prisma";
import Link from "next/link";
import { 
  Plus, 
  Search, 
  ShieldCheck, 
  Download, 
  Fingerprint, 
  Calendar, 
  Globe, 
  MapPin,
  ExternalLink
} from "lucide-react";
import PassportForm from "@/components/ops/PassportForm";

export default async function PassportVaultPage() {
  const [passports, clients] = await Promise.all([
    prisma.travelDocument.findMany({
      where: { type: 'passport' },
      include: { client: true },
      orderBy: { created_at: 'desc' }
    }),
    prisma.client.findMany({
      select: { id: true, name: true },
      orderBy: { name: 'asc' }
    })
  ]);

  return (
    <div className="space-y-8 font-outfit uppercase font-black">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic font-black uppercase">Passport Vault</h1>
          <p className="text-slate-400 font-bold text-[10px] tracking-[0.2em] uppercase mt-1 italic">Secure Sovereign Identity Management</p>
        </div>
        <PassportForm clients={clients} />
      </div>

      {/* Vault Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        {passports.length > 0 ? passports.map((doc) => (
          <div key={doc.id} className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden flex flex-col justify-between min-h-[450px] italic">
             <div>
                <div className="flex justify-between items-start mb-6">
                   <div className="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center border border-indigo-100 shadow-sm">
                      <Fingerprint size={28} />
                   </div>
                   <div className="text-right">
                      <div className="flex items-center gap-2 justify-end mb-1">
                         <div className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
                         <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest italic tracking-tight">Vaulted</p>
                      </div>
                      <p className="text-[10px] font-bold text-slate-900 tracking-widest">ARC: {doc.id.toString().padStart(5, '0')}</p>
                   </div>
                </div>

                <div className="space-y-6">
                   <div>
                      <h3 className="text-lg font-black text-slate-900 uppercase tracking-tight truncate pr-4 leading-none italic">{doc.client.name}</h3>
                      <p className="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] mt-3 italic flex items-center gap-2">
                        <Globe size={10} /> {doc.nationality || 'UNKNOWN_ORIGIN'} 
                      </p>
                   </div>

                   <div className="p-4 bg-slate-900 rounded-2xl text-white shadow-xl shadow-slate-900/20 relative overflow-hidden">
                      <div className="relative z-10 flex justify-between items-center">
                         <div>
                            <p className="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Document Number</p>
                            <p className="text-lg font-black tracking-[0.2em]">{doc.passport_number}</p>
                         </div>
                         <div className="text-right">
                            <p className="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Expires</p>
                            <p className={`text-xs font-black tracking-widest ${doc.expiry_date && new Date(doc.expiry_date) < new Date() ? 'text-red-400' : 'text-emerald-400'}`}>
                               {doc.expiry_date ? new Date(doc.expiry_date).toLocaleDateString() : 'N/A'}
                            </p>
                         </div>
                      </div>
                      <div className="absolute top-0 right-0 p-8 opacity-5">
                         <ShieldCheck size={100} />
                      </div>
                   </div>

                   <div className="grid grid-cols-2 gap-4">
                      <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                         <p className="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Place of Issue</p>
                         <p className="text-[10px] font-black text-slate-900 tracking-widest truncate">{doc.place_of_issue || 'UNDEFINED'}</p>
                      </div>
                      <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                         <p className="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Gender</p>
                         <p className="text-[10px] font-black text-slate-900 tracking-widest uppercase">{doc.gender || 'N/A'}</p>
                      </div>
                   </div>
                </div>
             </div>

             <div className="flex items-center justify-between pt-8 mt-8 border-t border-slate-50">
                <div className="flex items-center gap-4">
                   <div className="flex flex-col">
                      <span className="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">Archived On</span>
                      <span className="text-[10px] font-black text-slate-700 mt-1">{new Date(doc.created_at).toLocaleDateString()}</span>
                   </div>
                </div>
                <div className="flex gap-2">
                   <Link 
                    href={`/api/vault/passport/${doc.id}`}
                    target="_blank"
                    className="p-3 bg-slate-50 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 border border-slate-100 hover:border-indigo-100 rounded-2xl transition-all shadow-sm flex items-center gap-2"
                   >
                     <Download size={18} />
                     <span className="text-[9px] font-black">GET SCAN</span>
                   </Link>
                </div>
             </div>
          </div>
        )) : (
          <div className="col-span-full py-40 bg-white rounded-[2.5rem] border-2 border-slate-100 border-dashed text-center flex flex-col items-center gap-6 shadow-inner">
             <div className="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                <Fingerprint size={56} />
             </div>
             <div>
                <p className="text-slate-900 font-black text-2xl uppercase tracking-tighter italic font-outfit">Sovereign Vault Empty</p>
                <p className="text-slate-400 font-bold text-xs uppercase tracking-[0.3em] mt-2 italic">Upload biometric documents to initiate identity ledger</p>
             </div>
          </div>
        )}
      </div>
    </div>
  );
}
