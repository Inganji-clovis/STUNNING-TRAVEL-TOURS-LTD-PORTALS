import LoginForm from "@/components/auth/LoginForm";
import Image from "next/image";

export default function LoginPage() {
  return (
    <div className="min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-900">
      {/* Visual Background Layer */}
      <div className="absolute inset-0 z-0">
        <div 
          className="absolute inset-0 bg-cover bg-center" 
          style={{ backgroundImage: "url('/assets/brand/clear_plane.png')" }}
        />
        <div className="absolute inset-0 bg-black/40" />
      </div>

      <div className="w-full max-w-5xl rounded-[1.5rem] overflow-hidden flex flex-col lg:flex-row shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] border border-white/10 relative z-10 mx-4">
        {/* Visual Side with Quote */}
        <div className="lg:w-1/2 relative min-h-[300px] lg:min-h-[500px] overflow-hidden flex items-end">
          <div className="absolute inset-0 bg-gradient-to-t from-slate-900/95 via-slate-900/40 to-black/20 backdrop-blur-[2px]" />
          <div className="p-10 lg:p-14 text-white relative z-10 flex flex-col justify-end h-full">
            <svg className="w-10 h-10 text-red-500 mb-6 opacity-80" fill="currentColor" viewBox="0 0 24 24">
              <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.053 20 5.762 20 9.549L20 10.609L24 10.609L24 18L14.017 18ZM0 18L0 10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.053 6 5.762 6 9.549L6 10.609L10 10.609L10 18L0 18Z" />
            </svg>
            
            <h2 className="text-3xl lg:text-4xl font-black tracking-tight mb-4 leading-[1.2] text-white">
              "Success is no accident. It is <span className="text-red-500 italic">hard work</span>, perseverance, learning, studying, sacrifice and most of all, love of what you are doing."
            </h2>
            <div className="flex items-center space-x-4 mt-4">
              <div className="h-1 w-12 bg-red-500 rounded-full" />
              <p className="text-white/70 font-bold text-sm tracking-widest uppercase">Pelé</p>
            </div>
          </div>
        </div>

        {/* Form Side */}
        <div className="lg:w-1/2 bg-white/95 backdrop-blur-3xl p-10 lg:p-14 flex flex-col justify-center relative">
          <div className="mb-10 w-full text-center">
            <div className="w-full h-40 mb-2 flex items-center justify-center relative">
              <Image 
                src="/assets/brand/logo-removebg-preview.png" 
                alt="Logo" 
                fill 
                className="object-contain"
                priority
              />
            </div>
            <h1 className="text-3xl font-black text-slate-900 tracking-tight mb-2 uppercase">Welcome</h1>
            <p className="text-slate-500 font-bold text-sm uppercase tracking-widest opacity-60">staff portal</p>
          </div>

          <LoginForm />

          <div className="mt-8 text-center">
            <p className="text-sm font-black text-slate-400 uppercase tracking-widest">
              STUNNING TRAVEL & TOURS <span className="mx-2 opacity-30">|</span> RWANDA HQ
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}
