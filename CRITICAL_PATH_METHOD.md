# Critical Path Method (CPM) Implementation

## Overview
Critical Path Method (CPM) is a project management technique that identifies the longest sequence of dependent tasks that determines the minimum time required to complete a project. Tasks on the critical path have zero slack/float time, meaning any delay in these tasks will directly delay the project completion.

## How It Works

### 1. Forward Pass (Early Start & Early Finish)
Calculates the earliest possible start and finish times for each task:
- **Early Start (ES)**: The earliest time a task can begin
- **Early Finish (EF)**: The earliest time a task can complete
- Formula: `EF = ES + Duration`

### 2. Backward Pass (Late Start & Late Finish)
Calculates the latest allowable start and finish times without delaying the project:
- **Late Finish (LF)**: The latest time a task can complete without delaying project
- **Late Start (LS)**: The latest time a task can begin without delaying project
- Formula: `LS = LF - Duration`

### 3. Slack/Float Calculation
**Total Float** = LS - ES (or LF - EF)
- **Zero Float**: Critical task - any delay affects project
- **Positive Float**: Non-critical task - can be delayed without affecting project

### 4. Critical Path Identification
Tasks with Total Float = 0 form the Critical Path - the longest sequence that determines project duration.

## Database Schema

### New Fields in `tasks` Table
```php
'early_start'       => integer   // Earliest start day (0-based)
'early_finish'      => integer   // Earliest finish day
'late_start'        => integer   // Latest start day
'late_finish'       => integer   // Latest finish day
'total_float'       => integer   // Slack time in days
'is_critical'       => boolean   // True if on critical path
```

## API Endpoints

### Calculate Critical Path
```http
POST /projects/{project}/wbs/critical-path/calculate
```

**Response:**
```json
{
  "success": true,
  "message": "Critical path calculated successfully",
  "critical_path": [
    {
      "id": 1,
      "wbs_code": "1",
      "title": "Task A",
      "estimated_duration": 5,
      "early_start": 0,
      "early_finish": 5,
      "late_start": 0,
      "late_finish": 5,
      "total_float": 0,
      "assignee": "John Doe"
    }
  ],
  "project_duration": 15,
  "critical_path_count": 4,
  "total_tasks": 10
}
```

### View Critical Path
```http
GET /projects/{project}/wbs/critical-path
```
Returns the critical path analysis view with visual representation.

## Task Model Methods

### `calculateForwardPass()`
Calculates Early Start (ES) and Early Finish (EF) for a task:
```php
$task->calculateForwardPass();
```

Logic:
1. If no dependencies: ES = 0
2. If has dependencies: ES = max(predecessor EF) + lag_days
3. EF = ES + estimated_duration

### `calculateBackwardPass($projectFinish)`
Calculates Late Start (LS) and Late Finish (LF) for a task:
```php
$task->calculateBackwardPass($projectFinish);
```

Logic:
1. If no dependents: LF = project finish time
2. If has dependents: LF = min(successor LS) - lag_days
3. LS = LF - estimated_duration
4. Total Float = LS - ES
5. Is Critical = (Total Float == 0)

### Static Methods
```php
// Get all critical path tasks for a project
Task::getCriticalPath($projectId);

// Get project duration (max early finish)
Task::getProjectDuration($projectId);
```

## WBS Controller Methods

### `calculateCriticalPath(Project $project)`
Main CPM calculation method that:
1. Resets all CPM fields for project tasks
2. Performs forward pass (ES/EF calculation)
3. Performs backward pass (LS/LF calculation)
4. Identifies critical path tasks
5. Returns results as JSON

**Process:**
```php
1. Get all tasks with dependencies
2. Reset CPM fields to null
3. Forward Pass:
   - Start with tasks without dependencies
   - Process in topological order
   - Calculate ES and EF for each task
4. Get project finish time (max EF)
5. Backward Pass:
   - Start with tasks without dependents
   - Process in reverse topological order
   - Calculate LS, LF, Float, and Critical flag
6. Return critical path tasks sorted by ES
```

### `showCriticalPath(Project $project)`
View method that:
1. Calls `calculateCriticalPath()`
2. Retrieves all tasks with CPM data
3. Returns critical path view with results

## User Interface

### Critical Path View Components

#### 1. Summary Cards
- **Project Duration**: Total days from start to finish
- **Critical Tasks**: Number of tasks on critical path
- **Total Tasks**: All tasks in project
- **Critical %**: Percentage of critical tasks

#### 2. Critical Path Visualization
Sequential list showing:
- Task sequence number
- WBS code and title
- Assigned team member
- Duration, ES, and EF
- Critical badge
- Arrow connections between tasks

#### 3. All Tasks Analysis Table
Columns:
- WBS Code
- Task Name
- Duration
- ES (Early Start)
- EF (Early Finish)
- LS (Late Start)
- LF (Late Finish)
- Slack Time (Float)
- Status

Visual indicators:
- Red highlighting for critical tasks
- Green badges for positive slack
- Red badges for zero slack

#### 4. Legend
Explains CPM terminology and concepts

### WBS View Integration
- **Critical Path button** in header - Navigate to analysis view
- **Visual indicators** on critical tasks:
  - Red background with left border
  - "CRITICAL" badge
  - Highlighted in task list

## Example Calculation

### Sample Project
```
Task A: Duration 5 days (no dependencies)
Task B: Duration 3 days (depends on A, FS)
Task C: Duration 4 days (depends on A, FS)
Task D: Duration 2 days (depends on B and C, FS)
```

### Forward Pass
```
Task A: ES=0,  EF=5   (0 + 5)
Task B: ES=5,  EF=8   (5 + 3)
Task C: ES=5,  EF=9   (5 + 4)
Task D: ES=9,  EF=11  (max(8,9) + 2)
```
**Project Duration: 11 days**

### Backward Pass
```
Task D: LF=11, LS=9   (11 - 2), Float=0 ✓ CRITICAL
Task C: LF=9,  LS=5   (9 - 4),  Float=0 ✓ CRITICAL
Task B: LF=9,  LS=6   (9 - 3),  Float=1
Task A: LF=5,  LS=0   (5 - 5),  Float=0 ✓ CRITICAL
```

### Critical Path
**A → C → D** (Total: 11 days)

Task B has 1 day of slack - it can be delayed 1 day without affecting the project.

## Best Practices

### 1. Task Setup
- **Set estimated duration** for all tasks
- **Define dependencies** accurately
- **Avoid circular dependencies** (prevented by system)
- **Use appropriate dependency types** (usually FS)

### 2. Regular Recalculation
Recalculate critical path when:
- New tasks added
- Dependencies changed
- Task durations updated
- Task status changes

### 3. Project Management
- **Focus on critical tasks** - zero slack means no room for delay
- **Monitor critical path** regularly during project execution
- **Allocate resources** to critical tasks first
- **Track progress** of critical tasks closely

### 4. Slack Time Usage
- Tasks with slack can be:
  - Delayed without affecting project
  - Used for resource leveling
  - Lower priority for urgent resources

## Limitations & Considerations

### Current Implementation
1. **Day-based calculations**: Uses integer days (no hours/minutes)
2. **No resource constraints**: Assumes unlimited resources
3. **No working calendar**: Doesn't account for weekends/holidays
4. **Manual recalculation**: Must click "Recalculate" button

### Dependencies
- Requires tasks have `estimated_duration` set
- Requires valid dependency chain (no orphaned tasks)
- Works with all dependency types (FS, SS, FF, SF)
- Respects lag/lead time settings

## Troubleshooting

### No Critical Path Calculated
**Cause:** Tasks missing estimated_duration
**Solution:** Ensure all tasks have duration > 0

### Incorrect Project Duration
**Cause:** Dependencies not properly defined
**Solution:** Review task dependencies and ensure proper chain

### Tasks Not Showing as Critical
**Cause:** Calculation not run or tasks not on longest path
**Solution:** Click "Recalculate" button to update analysis

### Circular Dependency Error
**Cause:** Dependency loop (A→B→C→A)
**Solution:** Review and remove conflicting dependencies

## Future Enhancements

Potential improvements:
- **Automatic recalculation** on dependency changes
- **Gantt chart visualization** with critical path overlay
- **Resource-constrained scheduling** (critical chain method)
- **Monte Carlo simulation** for risk analysis
- **Baseline comparison** (planned vs actual critical path)
- **Multiple critical paths** detection
- **Critical chain buffer management**
- **Working calendar integration**
- **What-if analysis** tools
- **Export to project management formats** (MS Project, etc.)

## References

- Critical Path Method (CPM) - Project Management Body of Knowledge (PMBOK)
- Network Diagrams & CPM Analysis
- Schedule Network Analysis Techniques
- Float/Slack Time Calculations
