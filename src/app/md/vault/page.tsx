import { prisma } from "@/lib/prisma";
import { 
  Shield, 
  Search, 
  FileText, 
  Plus, 
  Download, 
  Eye, 
  Calendar,
  Filter,
  ArrowRight,
  Gavel,
  Scale,
  Building2,
  Lock,
  Briefcase
} from "lucide-react";
import Link from "next/link";
import { archiveCorporateDocument } from "@/lib/vault-actions";

export default async function MdVaultPage({
  searchParams,
}: {
  searchParams: Promise<{ search?: string; category?: string }>;
}) {
  const { search = "", category = "" } = await searchParams;

  const documents = await prisma.corporateDocument.findMany({
    where: {
      AND: [
        category ? { category } : {},
        search ? {
          OR: [
            { title: { contains: search } },
            { description: { contains: search } }
          ]
        } : {}
      ]
    },
    include: { uploader: true },
    orderBy: { created_at: "desc" }
  });

  const categories = [
    { id: "board", name: "Board Resolutions", icon: Gavel, color: "text-amber-600", bg: "bg-amber-50" },
    { id: "legal", name: "Legal Contracts", icon: Scale, color: "text-blue-600", bg: "bg-blue-50" },
    { id: "tax", name: "Tax Compliance", icon: Building2, color: "text-emerald-600", bg: "bg-emerald-50" },
    { id: "policy", name: "Internal Policy", icon: Shield, color: "text-purple-600", bg: "bg-purple-50" },
    { id: "other", name: "Other Archives", icon: Briefcase, color: "text-slate-600", bg: "bg-slate-50" },
  ];

  return (
    <div className="space-y-10 pb-20 italic">
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-10 rounded-[2.5rem] border border-slate-200 shadow-sm relative overflow-hidden">
        <div className="relative z-10">
          <div className="flex items-center gap-3 mb-2">
            <div className="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white">
              <Lock size={16} strokeWidth={3} />
            </div>
            <span className="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600">Secure Environment</span>
          </div>
          <h1 className="text-4xl font-black text-slate-900 tracking-tight uppercase">Corporate Vault</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-2">Executive Intelligence & Compliance Repository</p>
        </div>
        
        <div className="flex flex-wrap gap-4 relative z-10">
          <Link href="/md/folders" className="px-8 py-4 bg-white border-2 border-slate-900 text-slate-900 rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:bg-slate-50 transition-all">
            <Briefcase size={18} /> Folder Manager
          </Link>
          <button 
            onClick={() => document.getElementById('upload-modal')?.classList.remove('hidden')}
            className="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-xl shadow-slate-200 active:scale-95"
          >
            <Plus size={18} strokeWidth={3} /> Archive New Document
          </button>
        </div>

        <div className="absolute right-[-5%] top-[-20%] opacity-[0.03] rotate-12">
            <Shield size={400} />
        </div>
      </div>

      {/* Stats / Categories Grid */}
      <div className="grid grid-cols-2 md:grid-cols-5 gap-6">
        {categories.map((cat) => (
          <Link 
            key={cat.id}
            href={`/md/vault?category=${cat.id}`}
            className={`p-6 rounded-3xl border border-slate-100 ${category === cat.id ? 'ring-2 ring-slate-900 shadow-lg' : 'bg-white hover:shadow-md'} transition-all group`}
          >
            <div className={`w-12 h-12 rounded-2xl ${cat.bg} ${cat.color} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform`}>
              <cat.icon size={24} />
            </div>
            <h3 className="font-black text-xs uppercase tracking-tight text-slate-900">{cat.name}</h3>
            <p className="text-[9px] font-bold text-slate-400 uppercase mt-1 tracking-widest">
              {documents.filter(d => d.category === cat.id).length} Documents
            </p>
          </Link>
        ))}
      </div>

      {/* Search & Filters */}
      <div className="flex flex-col md:flex-row gap-6">
        <div className="flex-1 relative group">
          <Search className="absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors" size={20} />
          <input 
            type="text" 
            placeholder="Search by title, description or content tags..."
            className="w-full pl-16 pr-8 py-5 bg-white border border-slate-200 rounded-3xl font-black text-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-50 transition-all uppercase text-xs tracking-widest"
          />
        </div>
        <button className="px-8 py-5 bg-white border border-slate-200 rounded-3xl font-black uppercase tracking-widest text-[10px] text-slate-500 flex items-center gap-3 hover:text-slate-900 transition-all shadow-sm">
          <Filter size={18} /> Advanced Filters
        </button>
      </div>

      {/* Document List */}
      <div className="bg-white rounded-[3rem] border border-slate-200 overflow-hidden shadow-sm">
        <div className="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 className="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Live Archive Feed</h3>
            <span className="text-[10px] font-black text-slate-900 bg-white px-4 py-2 rounded-full border border-slate-100 uppercase tracking-widest shadow-sm">
              Sorted by: Newest First
            </span>
        </div>
        <div className="divide-y divide-slate-100">
          {documents.map((doc) => {
            const CatIcon = categories.find(c => c.id === doc.category)?.icon || FileText;
            return (
              <div key={doc.id} className="p-8 hover:bg-slate-50 transition-all group flex items-center justify-between gap-8">
                <div className="flex items-center gap-6 flex-1 min-w-0">
                  <div className="w-16 h-16 rounded-[1.25rem] bg-slate-900 flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform shadow-lg shadow-slate-200">
                    <CatIcon size={24} />
                  </div>
                  <div className="min-w-0">
                    <div className="flex items-center gap-3 mb-1">
                      <span className="text-[9px] font-black px-2 py-1 bg-slate-100 text-slate-500 rounded uppercase tracking-widest">{doc.category}</span>
                      <span className="text-[9px] font-black text-slate-300 uppercase tracking-widest">•</span>
                      <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1">
                        <Calendar size={10} /> {new Date(doc.created_at).toLocaleDateString()}
                      </span>
                    </div>
                    <h4 className="text-lg font-black text-slate-900 truncate uppercase tracking-tight">{doc.title}</h4>
                    <p className="text-xs font-bold text-slate-400 truncate uppercase tracking-widest mt-1 italic">{doc.description || "Internal archived document record"}</p>
                  </div>
                </div>

                <div className="flex items-center gap-3 shrink-0">
                   <a 
                    href={`/api/vault/corporate/${doc.id}`} 
                    target="_blank"
                    className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:border-slate-900 transition-all shadow-sm group/btn"
                   >
                     <Eye size={20} className="group-hover/btn:scale-110 transition-transform" />
                   </a>
                   <a 
                    href={`/api/vault/corporate/${doc.id}`}
                    download
                    className="h-12 px-6 rounded-2xl bg-slate-900 text-white flex items-center gap-3 font-black uppercase text-[9px] tracking-widest hover:scale-105 transition-all shadow-lg active:scale-95"
                   >
                     <Download size={16} /> Get Copy
                   </a>
                </div>
              </div>
            );
          })}
          
          {documents.length === 0 && (
            <div className="p-20 text-center space-y-4">
              <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-200">
                <FileText size={40} />
              </div>
              <p className="text-slate-400 font-black uppercase text-xs tracking-widest italic">No documents found matching your criteria</p>
            </div>
          )}
        </div>
      </div>

      {/* Upload Modal (Pseudo-Implementation using form) */}
      <div id="upload-modal" className="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 hidden flex items-center justify-center p-6 italic">
          <div className="bg-white rounded-[3rem] w-full max-w-2xl overflow-hidden shadow-2xl relative">
              <div className="p-10 border-b border-slate-100 flex items-center justify-between">
                  <div>
                      <h2 className="text-2xl font-black text-slate-900 uppercase">Archive Protocol</h2>
                      <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic">Submit corporate document to secure vault</p>
                  </div>
                  <button onClick={() => document.getElementById('upload-modal')?.classList.add('hidden')} className="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all">
                      <Plus size={20} className="rotate-45" />
                  </button>
              </div>
              <form action={archiveCorporateDocument} className="p-10 space-y-8">
                  <div className="grid grid-cols-2 gap-8">
                      <div className="space-y-3">
                          <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Document Title</label>
                          <input name="title" required type="text" className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic text-xs" />
                      </div>
                      <div className="space-y-3">
                          <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Archive Category</label>
                          <select name="category" required className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic text-xs">
                              {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                          </select>
                      </div>
                  </div>
                  <div className="space-y-3">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Secure File (Max 50MB)</label>
                      <input name="file" required type="file" className="w-full px-6 py-20 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl font-black text-slate-400 hover:border-slate-900 transition-all text-center uppercase text-[10px]" />
                  </div>
                  <div className="space-y-3">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Reference Notes (Optional)</label>
                      <textarea name="description" rows={3} className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic text-xs"></textarea>
                  </div>
                  <div className="pt-4 flex items-center justify-between">
                      <div className="flex items-center gap-2">
                        <Shield className="text-emerald-500" size={16} />
                        <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic">Vault Integrity Protocols Enabled</span>
                      </div>
                      <button className="px-12 py-5 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl">
                        Commit Archive <ArrowRight size={16} />
                      </button>
                  </div>
              </form>
          </div>
      </div>
    </div>
  );
}
