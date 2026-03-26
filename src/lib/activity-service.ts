import { prisma } from "./prisma";
import { auth } from "@/auth";

export type ActivityPortal = "Admin" | "Sales" | "Operations" | "MD";

export class ActivityLogger {
  static async log(
    portal: ActivityPortal,
    module: string,
    action: string,
    target: string,
    description: string
  ) {
    const session = await auth();
    if (!session?.user) return; // Silent skip if no session

    try {
      await prisma.activityLog.create({
        data: {
          user_id: parseInt(session.user.id),
          portal,
          module,
          action,
          target,
          description,
        },
      });
    } catch (error) {
      console.error("Failed to log activity:", error);
    }
  }
}
