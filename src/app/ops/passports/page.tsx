import { prisma } from "@/lib/prisma";
import { 
  FileText, 
  Search, 
  Filter, 
  Calendar, 
  AlertTriangle, 
  CheckCircle2, 
  Download,
  Plus,
  ArrowRight,
  Clock
} from "lucide-react";
import Link from "next/link";

export default async function PassportVaultPage() {
  const documents = await prisma.travelDocument.findMany({
    where: { type: 'passport' },
    orderBy: { expiry_date: 'asc' },
    include: { client: true }
  });

  const now = new Date();

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase">Passport Vault</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1">Secure travel document repository</p>
        </div>
        <button className="flex items-center gap-3 px-8 py-4 bg-emerald-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-emerald-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
          <Plus size={16} /> Record Passport
        </button>
      </div>

       {/* Grid View */}
       <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        {documents.length > 0 ? documents.map((doc) => {
          const isExpired = doc.expiry_date ? new Date(doc.expiry_date) < now : false;
          const expiryTime = doc.expiry_date ? new Date(doc.expiry_date).getTime() : 0;
          const isExpiringSoon = doc.expiry_date && !isExpired && (expiryTime - now.getTime()) < (1000 * 60 * 60 * 24 * 90);

          return (
            <div key={doc.id} className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden">
               <div className={`absolute top-0 right-0 w-32 h-32 ${isExpired ? 'bg-red-50' : isExpiringSoon ? 'bg-amber-50' : 'bg-emerald-50'} rounded-full -mr-16 -mt-16 opacity-40 group-hover:scale-110 transition-transform`} />
               
               <div className="flex justify-between items-start mb-6 relative z-10">
                  <div className={`w-12 h-12 ${isExpired ? 'bg-red-50 text-red-600 border-red-100' : isExpiringSoon ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100'} rounded-2xl flex items-center justify-center border shadow-sm`}>
                     <FileText size={24} />
                  </div>
                  <div className="text-right">
                     <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Passport No.</p>
                     <p className="text-sm font-black text-slate-900 tracking-tight">{doc.passport_number}</p>
                  </div>
               </div>

               <div className="space-y-4 relative z-10">
                  <div>
                     <h3 className="text-lg font-black text-slate-900 uppercase tracking-tight truncate">{doc.client.name}</h3>
                     <p className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">{doc.nationality} Citizen</p>
                  </div>

                  <div className={`p-5 rounded-2xl border flex items-center justify-between ${isExpired ? 'bg-red-50 border-red-100' : isExpiringSoon ? 'bg-amber-50 border-amber-100' : 'bg-slate-50 border-slate-100'}`}>
                     <div>
                        <p className="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1 italic">Validity Limit</p>
                         <p className={`text-sm font-black ${isExpired ? 'text-red-700' : isExpiringSoon ? 'text-amber-700' : 'text-slate-700'}`}>
                            {doc.expiry_date ? new Date(doc.expiry_date).toLocaleDateString() : 'NO EXPIRY'}
                         </p>
                     </div>
                     {isExpired ? (
                        <AlertTriangle className="text-red-600" size={20} />
                     ) : isExpiringSoon ? (
                        <Clock className="text-amber-600" size={20} />
                     ) : (
                        <CheckCircle2 className="text-emerald-600" size={20} />
                     )}
                  </div>

                  <div className="flex items-center justify-between pt-2">
                     <button className="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-emerald-600 transition-colors">
                        View Details <ArrowRight size={12} />
                     </button>
                     <button className="p-2 text-slate-400 hover:text-blue-600 transition-colors">
                        <Download size={18} />
                     </button>
                  </div>
               </div>
            </div>
          );
        }) : (
          <div className="col-span-full py-20 bg-white rounded-[2.5rem] border border-slate-200 border-dashed text-center flex flex-col items-center gap-4 opacity-40">
             <FileText size={48} className="text-slate-300" />
             <p className="text-slate-400 font-black text-sm uppercase tracking-widest italic">The passport vault is currently empty</p>
          </div>
        )}
      </div>
    </div>
  );
}
