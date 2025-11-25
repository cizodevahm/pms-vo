<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AssignManagersToProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:assign-managers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign project managers to existing projects that have no manager assigned';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projectsWithoutManager = \App\Models\Project::whereNull('manager_id')->get();
        $projectManagers = \App\Models\User::where('role', 'Project Manager')->get();

        if ($projectManagers->isEmpty()) {
            $this->error('No Project Managers found in the system.');
            return 1;
        }

        if ($projectsWithoutManager->isEmpty()) {
            $this->info('All projects already have managers assigned.');
            return 0;
        }

        $this->info("Found {$projectsWithoutManager->count()} projects without managers.");
        $this->info("Available Project Managers: {$projectManagers->count()}");

        $managerIndex = 0;
        foreach ($projectsWithoutManager as $project) {
            $manager = $projectManagers[$managerIndex % $projectManagers->count()];
            $project->manager_id = $manager->id;
            $project->save();

            $this->line("Assigned '{$project->name}' to '{$manager->name}'");
            $managerIndex++;
        }

        $this->info("\nSuccessfully assigned managers to {$projectsWithoutManager->count()} projects.");
        return 0;
    }
}
