import { prisma } from "@/lib/prisma";
import { 
  Plus, 
  Search, 
  ShieldCheck, 
  FileText, 
  Download,
  Calendar,
  Building2,
  Link as LinkIcon
} from "lucide-react";
import Link from "next/link";
import EbmForm from "@/components/sales/EbmForm";

export default async function EbmRegistryPage() {
  const [ebms, invoices] = await Promise.all([
    prisma.ebm.findMany({
      orderBy: { date: 'desc' },
      include: { 
        uploader: true,
        invoice: true
      }
    }),
    prisma.billingDocument.findMany({
      where: { type: 'invoice' },
      select: { id: true, document_number: true },
      orderBy: { created_at: 'desc' }
    })
  ]);

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic font-outfit">EBM Registry</h1>
          <p className="text-slate-400 font-bold text-[10px] tracking-[0.2em] uppercase mt-1">Electronic Billing Machine Archives</p>
        </div>
        <EbmForm invoices={invoices} />
      </div>

      {/* Grid View */}
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        {ebms.length > 0 ? ebms.map((ebm) => (
          <div key={ebm.id} className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden flex flex-col justify-between min-h-[400px]">
             <div>
                <div className="flex justify-between items-start mb-6">
                   <div className="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center border border-blue-100 shadow-sm">
                      <ShieldCheck size={24} />
                   </div>
                   <div className="text-right">
                      <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Receipt Date</p>
                      <p className="text-xs font-bold text-slate-900">{new Date(ebm.date).toLocaleDateString()}</p>
                   </div>
                </div>

                <div className="space-y-4">
                   <div>
                      <h3 className="text-lg font-black text-slate-900 uppercase tracking-tight truncate pr-4">{ebm.company_name}</h3>
                      <div className="flex items-center gap-2 mt-1">
                        <p className="text-[10px] font-black text-blue-600 uppercase tracking-widest italic">TIN: {ebm.tin_number}</p>
                        {ebm.invoice && (
                          <div className="flex items-center gap-1 px-2 py-0.5 bg-slate-100 text-slate-500 rounded-lg border border-slate-200">
                             <LinkIcon size={10} />
                             <span className="text-[8px] font-black uppercase tracking-tighter">INV: {ebm.invoice.document_number}</span>
                          </div>
                        )}
                      </div>
                   </div>

                   <div className="grid grid-cols-2 gap-3">
                      <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                         <p className="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Net Amount</p>
                         <p className="text-lg font-black text-slate-900 tracking-tighter">RWF {Number(ebm.amount_rwf).toLocaleString()}</p>
                      </div>
                      <div className="p-4 bg-blue-50/50 rounded-2xl border border-blue-100/50">
                         <p className="text-[8px] font-black text-blue-400 uppercase tracking-widest mb-1 italic">VAT</p>
                         <p className="text-lg font-black text-blue-600 tracking-tighter">RWF {Number(ebm.vat_amount || 0).toLocaleString()}</p>
                      </div>
                   </div>

                   {ebm.description && (
                     <div className="p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                        <p className="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Notes</p>
                        <p className="text-[10px] font-bold text-slate-500 italic leading-relaxed line-clamp-2">{ebm.description}</p>
                     </div>
                   )}
                </div>
             </div>

             <div className="flex items-center justify-between pt-6 mt-6 border-t border-slate-50">
                <div className="flex items-center gap-2">
                   <div className="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-500 uppercase border border-slate-200">
                      {ebm.uploader.name[0]}
                   </div>
                   <div>
                      <span className="block text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Uploaded By</span>
                      <span className="block text-[10px] font-bold text-slate-700">{ebm.uploader.name}</span>
                   </div>
                </div>
                <Link 
                  href={`/api/vault/ebm/${ebm.id}`}
                  className="p-3 bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 border border-slate-100 hover:border-blue-100 rounded-2xl transition-all shadow-sm"
                  target="_blank"
                >
                   <Download size={18} />
                </Link>
             </div>
          </div>
        )) : (
          <div className="col-span-full py-20 bg-white rounded-[2.5rem] border-2 border-slate-100 border-dashed text-center flex flex-col items-center gap-4">
             <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300">
                <ShieldCheck size={48} />
             </div>
             <div>
                <p className="text-slate-900 font-black text-lg uppercase tracking-tight italic">No archived EBM receipts</p>
                <p className="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mt-1 italic">Start a new archive to populate the registry</p>
             </div>
          </div>
        )}
      </div>
    </div>
  );
}
