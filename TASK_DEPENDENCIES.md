# Task Dependencies & Relationships Feature

## Overview
This feature enables project managers to define dependencies between tasks, allowing for automatic scheduling and dependency visualization in the Work Breakdown Structure (WBS).

## Features Implemented

### 1. Database Structure
- **task_dependencies table**: Stores relationships between tasks
  - `task_id`: The task that has the dependency
  - `depends_on_task_id`: The task that must be completed first
  - `dependency_type`: Type of dependency (FS, SS, FF, SF)
  - `lag_days`: Lead/lag time in days (positive = delay, negative = lead)

- **tasks table additions**:
  - `estimated_duration`: Estimated task duration in days
  - `calculated_start_date`: Automatically calculated start date based on dependencies
  - `calculated_end_date`: Automatically calculated end date based on dependencies

### 2. Dependency Types

#### Finish-to-Start (FS) - Default
The most common dependency type. Task B cannot start until Task A finishes.
```
Task A: |====|
Task B:      |====|
```

#### Start-to-Start (SS)
Task B cannot start until Task A starts (they can run in parallel).
```
Task A: |========|
Task B:  |====|
```

#### Finish-to-Finish (FF)
Task B cannot finish until Task A finishes.
```
Task A: |====|
Task B: |========|
```

#### Start-to-Finish (SF)
Task B cannot finish until Task A starts (rare).
```
Task A:    |====|
Task B: |====|
```

### 3. Lag/Lead Time
- **Positive lag**: Delay between tasks (e.g., +5 days = 5 day wait period)
- **Negative lag**: Lead time (e.g., -2 days = Task B can start 2 days before Task A finishes)

## User Interface

### Managing Dependencies
1. Navigate to a project's WBS view
2. Hover over a task to reveal action buttons
3. Click the **Dependencies** button (chain icon)
4. In the modal:
   - **Add Dependency**: Select predecessor task, type, and lag time
   - **View Dependencies**: See tasks this depends on (with ← icon)
   - **View Dependents**: See tasks that depend on this (with → icon)
   - **Remove Dependency**: Click the × button on any dependency

### Visual Indicators
- **Chain icon** (🔗): Task has dependencies
- **Share nodes icon** (⚡): Other tasks depend on this task
- Dependency icons are color-coded:
  - Purple: Dependencies
  - Blue: Dependents

## Backend Logic

### Circular Dependency Prevention
The system prevents circular dependencies using recursive validation:
```php
// Example: A → B → C → A would be rejected
wouldCreateCircularDependency($taskId, $dependsOnTaskId)
```

### Automatic Date Calculation
When dependencies are added or removed, the system automatically:
1. Calculates start dates based on predecessor completion
2. Calculates end dates using estimated duration
3. Applies lag/lead time adjustments
4. Propagates changes through the entire dependency chain

```php
// Triggered after dependency changes
$task->calculateDates();
recalculateTaskDates($projectId);
```

### Task Start Validation
```php
$task->canStart(); // Returns true if all dependencies are completed
```

## API Endpoints

### Get Task Dependencies
```http
GET /projects/{project}/wbs/{task}/dependencies
```
Returns:
- Current task info
- Tasks this depends on (dependencies)
- Tasks that depend on this (dependents)
- Available tasks for adding new dependencies
- Whether task can start

### Add Dependency
```http
POST /projects/{project}/wbs/dependencies
Content-Type: application/json

{
  "task_id": 1,
  "depends_on_task_id": 2,
  "dependency_type": "finish-to-start",
  "lag_days": 0
}
```

### Remove Dependency
```http
DELETE /projects/{project}/wbs/dependencies/{dependency}
```

## Model Relationships

### Task Model
```php
// Tasks this depends on
$task->dependencies(); // HasMany TaskDependency
$task->dependsOnTasks(); // BelongsToMany Task

// Tasks that depend on this
$task->dependents(); // HasMany TaskDependency
$task->dependentTasks(); // BelongsToMany Task

// Helper methods
$task->hasDependencies(); // bool
$task->hasDependents(); // bool
$task->canStart(); // bool
$task->calculateDates(); // void
```

### TaskDependency Model
```php
$dependency->task(); // BelongsTo Task
$dependency->dependsOnTask(); // BelongsTo Task
$dependency->getDependencyTypeLabel(); // string
$dependency->getLagDescription(); // string
```

## Usage Examples

### Example 1: Simple Sequential Tasks
```
Design Phase (5 days)
  ↓ (Finish-to-Start)
Development Phase (10 days)
  ↓ (Finish-to-Start)
Testing Phase (3 days)
```

### Example 2: Parallel Tasks with Start-to-Start
```
Backend Development (10 days)
  ↓ (Start-to-Start)
Frontend Development (8 days)
```

### Example 3: With Lag Time
```
Concrete Pour (1 day)
  ↓ (Finish-to-Start, +7 days lag for curing)
Framing (5 days)
```

## Best Practices

1. **Use FS dependencies by default**: Most task relationships are finish-to-start
2. **Add estimated duration**: Required for automatic date calculation
3. **Check for circular dependencies**: The system prevents them, but plan carefully
4. **Use lag time for mandatory delays**: Like curing time, approval periods, etc.
5. **Review dependent tasks**: Before making changes, check what depends on a task
6. **Keep dependency chains manageable**: Too many dependencies can complicate scheduling

## Troubleshooting

### "Cannot create dependency: Would create circular dependency"
- Check if adding this dependency would create a loop
- Example: A → B → C → A (not allowed)
- Solution: Review your task flow and remove conflicting dependencies

### Dates not calculating automatically
- Ensure `estimated_duration` is set for all tasks
- Dependencies must form a valid chain
- Parent tasks must be completed for FS dependencies

### Task shows as "Cannot Start"
- Check `$task->canStart()` status
- Verify all predecessor tasks are completed
- Review dependency types and lag times

## Future Enhancements

Potential improvements for future versions:
- Visual dependency graph/Gantt chart
- Critical path analysis
- Resource leveling
- Baseline comparison
- Dependency templates
- Drag-and-drop dependency creation
- Auto-scheduling based on resource availability
