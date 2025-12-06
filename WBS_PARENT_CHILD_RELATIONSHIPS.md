# WBS Parent-Child Relationships & Sub-tasks

## Overview
The Work Breakdown Structure (WBS) implements a complete hierarchical task management system with unlimited nesting levels, allowing you to break down complex projects into manageable sub-tasks.

## Features Implemented

### 1. **Database Schema**

#### Tasks Table Columns
```php
'parent_id'    => integer (nullable) // FK to parent task
'level'        => integer            // Depth in hierarchy (0 = root)
'order'        => integer            // Position among siblings
'wbs_code'     => string             // Hierarchical code (1, 1.1, 1.1.1)
```

### 2. **Model Relationships**

#### Task Model Methods

```php
// Parent task relationship
parent(): BelongsTo
// Returns the parent task

// Children tasks (subtasks)
children(): HasMany
// Returns immediate children, ordered by 'order'

// Get all descendants recursively
descendants(): HasMany
// Returns all children, grandchildren, etc.

// Get all ancestors up to root
ancestors(): Collection
// Returns all parent tasks up to root level

// Check if task is a parent
isParent(): bool
// Returns true if task has children

// Check if task is root level
isRoot(): bool
// Returns true if task has no parent

// Get root tasks for a project
rootTasks($projectId): Collection
// Returns all top-level tasks

// Update WBS code based on hierarchy
updateWbsCode(): void
// Generates hierarchical codes (1, 1.1, 1.2, 2, 2.1, etc.)
```

## User Interface

### Visual Hierarchy

#### 1. **Indentation**
Tasks are visually indented based on their level:
- Root tasks: No indentation
- Level 1 subtasks: 8px left margin (ml-8)
- Level 2 subtasks: 16px left margin (ml-16)
- Level 3 subtasks: 24px left margin (ml-24)
- And so on...

```blade
@php
    $indentClass = 'ml-' . ($level * 8);
@endphp
```

#### 2. **WBS Code Display**
Each task displays its hierarchical code:
```
1           Root Task 1
  1.1       Subtask 1.1
    1.1.1   Sub-subtask 1.1.1
    1.1.2   Sub-subtask 1.1.2
  1.2       Subtask 1.2
2           Root Task 2
  2.1       Subtask 2.1
```

#### 3. **Expand/Collapse Controls**

**Chevron Icons:**
- 🔽 Chevron down - Task is expanded (children visible)
- 🔼 Chevron right - Task is collapsed (children hidden)
- Empty space - Task has no children

**Expand/Collapse All Buttons:**
- "Expand All" - Shows all tasks and subtasks
- "Collapse All" - Hides all subtasks, shows only root tasks

### Adding Sub-tasks

#### Method 1: Add Root Task
```javascript
// Click "Add Root Task" button in header
openAddRootTaskModal()
```
- Opens modal with parent_id = null
- Task will be created at root level

#### Method 2: Add Subtask
```javascript
// Click "+" button on any task item
openAddSubtask({id: taskId, title: 'Parent Task Name'})
```
- Opens modal with parent_id set to selected task
- Shows parent task name in form
- Task will be created as child of selected task

## Workflow Examples

### Example 1: Simple Project Structure
```
Project: Website Development
├── 1. Design Phase
│   ├── 1.1 Wireframes
│   ├── 1.2 Mockups
│   └── 1.3 Design Review
├── 2. Development Phase
│   ├── 2.1 Frontend
│   │   ├── 2.1.1 HTML/CSS
│   │   └── 2.1.2 JavaScript
│   └── 2.2 Backend
│       ├── 2.2.1 Database Design
│       ├── 2.2.2 API Development
│       └── 2.2.3 Authentication
└── 3. Testing Phase
    ├── 3.1 Unit Tests
    ├── 3.2 Integration Tests
    └── 3.3 User Acceptance Testing
```

### Example 2: Creating Nested Structure

**Step 1: Create Root Task**
1. Click "Add Root Task"
2. Enter: "Design Phase"
3. Submit
4. WBS Code assigned: `1`

**Step 2: Add First Subtask**
1. Hover over "Design Phase"
2. Click "+" button
3. Enter: "Wireframes"
4. Submit
5. WBS Code assigned: `1.1`

**Step 3: Add Sibling Subtask**
1. Hover over "Design Phase" again
2. Click "+" button
3. Enter: "Mockups"
4. Submit
5. WBS Code assigned: `1.2`

**Step 4: Add Sub-subtask**
1. Hover over "Wireframes"
2. Click "+" button
3. Enter: "Homepage Wireframe"
4. Submit
5. WBS Code assigned: `1.1.1`

## Backend Logic

### WBS Code Generation

The system automatically generates hierarchical codes when tasks are created or reordered:

```php
public function updateWbsCode(): void
{
    if ($this->isRoot()) {
        // Root level: 1, 2, 3...
        $this->wbs_code = (string)($this->order + 1);
        $this->level = 0;
    } else {
        // Child level: parent_code.order (e.g., 1.1, 1.2, 2.1.3)
        $parentCode = $this->parent->wbs_code ?? '0';
        $this->wbs_code = $parentCode . '.' . ($this->order + 1);
        $this->level = $this->parent->level + 1;
    }

    $this->saveQuietly(); // Save without triggering events

    // Update children recursively
    foreach ($this->children as $child) {
        $child->updateWbsCode();
    }
}
```

### Task Creation with Parent

```php
// In WbsController@store
$task = Task::create([
    'project_id' => $project->id,
    'parent_id' => $validated['parent_id'] ?? null, // Set parent
    'title' => $validated['title'],
    'order' => $maxOrder + 1, // Position among siblings
    // ... other fields
]);

// Regenerate WBS codes for entire project
$this->regenerateWbsCodes($project);
```

### Recursive Operations

**Deleting a Task:**
```php
// Cascade delete - removes task and ALL descendants
$task->delete();
// Children are automatically deleted via database cascade
```

**Loading Task Tree:**
```php
// Load 3 levels deep
$tasks = Task::rootTasks($project->id);
$tasks->load(['children.children.children', 'assignee']);
```

## JavaScript Functions

### Modal Management

```javascript
// Open modal for root task
function openAddRootTaskModal() {
    resetTaskForm();
    document.getElementById('taskParentId').value = '';
    document.getElementById('parentTaskInfo').style.display = 'none';
    document.getElementById('addTaskModal').style.display = 'flex';
}

// Open modal for subtask
function openAddSubtask(parentTask) {
    resetTaskForm();
    document.getElementById('taskParentId').value = parentTask.id;
    document.getElementById('parentTaskTitle').value = parentTask.title;
    document.getElementById('parentTaskInfo').style.display = 'block';
    document.getElementById('addTaskModal').style.display = 'flex';
}
```

### Tree Navigation

```javascript
// Toggle single task children
function toggleTask(taskId) {
    const children = document.getElementById(`children-${taskId}`);
    const icon = document.getElementById(`icon-${taskId}`);
    
    if (children.style.display === 'none') {
        children.style.display = 'block';
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
    } else {
        children.style.display = 'none';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
    }
}

// Expand all tasks
function expandAll() {
    document.querySelectorAll('[data-task-collapse]').forEach(el => {
        el.style.display = 'block';
    });
    document.querySelectorAll('[data-expand-icon]').forEach(el => {
        el.classList.remove('fa-chevron-right');
        el.classList.add('fa-chevron-down');
    });
}

// Collapse all tasks
function collapseAll() {
    document.querySelectorAll('[data-task-collapse]').forEach(el => {
        el.style.display = 'none';
    });
    document.querySelectorAll('[data-expand-icon]').forEach(el => {
        el.classList.remove('fa-chevron-down');
        el.classList.add('fa-chevron-right');
    });
}
```

## Visual Indicators

### Task Item Components

```blade
<!-- Expand/Collapse Button (if has children) -->
<button onclick="toggleTask({{ $task->id }})">
    <i id="icon-{{ $task->id }}" class="fas fa-chevron-down"></i>
</button>

<!-- WBS Code Display -->
<span class="text-xs font-mono font-semibold text-primary-600">
    {{ $task->wbs_code }}
</span>

<!-- Task Title with Badges -->
<h4 class="font-medium text-gray-900 truncate">
    {{ $task->title }}
</h4>
@if($isCritical)
    <span class="px-2 py-0.5 bg-red-600 text-white text-xs">
        CRITICAL
    </span>
@endif

<!-- Add Subtask Button -->
<button onclick="openAddSubtask({id: {{ $task->id }}, title: '{{ $task->title }}'})">
    <i class="fas fa-plus"></i>
</button>
```

## Best Practices

### 1. **Logical Breakdown**
- Break down large tasks into 3-7 subtasks
- Keep hierarchy depth manageable (typically 3-4 levels)
- Each level should represent a meaningful decomposition

### 2. **Naming Conventions**
- Root tasks: High-level phases or deliverables
- Subtasks: Specific activities or work packages
- Sub-subtasks: Detailed actions or tasks

### 3. **WBS Code Usage**
- Use for referencing tasks in communications
- Include in task IDs for documentation
- Useful for progress tracking and reporting

### 4. **Dependency Planning**
- Dependencies can be set between any tasks (siblings, cousins, etc.)
- Consider parent task completion when planning dependencies
- Critical path calculation considers entire hierarchy

## Integration with Other Features

### With Dependencies
- Dependencies work across hierarchy levels
- A subtask can depend on tasks from different branches
- Parent tasks inherit critical status if all children are critical

### With Critical Path
- Critical path calculation traverses entire hierarchy
- Sub-tasks included in critical path if they have zero slack
- Visual indicators show critical tasks at all levels

### With Gantt Charts (Future)
- Hierarchy will be visualized in Gantt view
- Summary bars for parent tasks
- Collapsible branches for better overview

## Limitations & Considerations

### Current Implementation
1. **Unlimited Nesting:** No hard limit on hierarchy depth (use wisely)
2. **Cascade Delete:** Deleting parent removes ALL descendants
3. **Order Management:** Siblings are ordered within their level
4. **Load Performance:** Deep hierarchies loaded 3 levels at a time

### Database Considerations
- `parent_id` is nullable foreign key
- Cascade delete configured at database level
- Ordering managed by `order` column within each parent

## API Endpoints

### Create Task with Parent
```http
POST /projects/{project}/wbs
Content-Type: application/json

{
  "title": "Subtask Name",
  "parent_id": 123,  // Parent task ID
  "description": "...",
  "priority": "medium",
  "assigned_to": 1,
  "estimated_duration": 5
}
```

### Get Task Tree
```http
GET /projects/{project}/wbs/tree

Response:
{
  "success": true,
  "tree": [
    {
      "id": 1,
      "title": "Root Task",
      "wbs_code": "1",
      "children": [
        {
          "id": 2,
          "title": "Subtask",
          "wbs_code": "1.1",
          "children": [...]
        }
      ]
    }
  ]
}
```

## Troubleshooting

### WBS Codes Not Updating
**Cause:** Manual modification of task order
**Solution:** Call `regenerateWbsCodes()` after any reordering

### Task Not Appearing Under Parent
**Cause:** `parent_id` not set correctly
**Solution:** Verify parent_id in database, regenerate codes

### Children Not Showing
**Cause:** Task is collapsed or CSS issue
**Solution:** Click expand button, check `display: none` style

### Deep Nesting Performance
**Cause:** Too many levels loaded at once
**Solution:** Use lazy loading or limit display depth

## Future Enhancements

Potential improvements:
- **Drag-and-drop reordering** - Move tasks between parents
- **Bulk operations** - Move multiple subtasks at once
- **Template hierarchies** - Save and reuse WBS structures
- **Auto-collapse on load** - Start with everything collapsed
- **Depth limits** - Configurable maximum nesting
- **Parent task summaries** - Aggregate child task data
- **Milestone tasks** - Special parent tasks with zero duration
- **Work package management** - Group related tasks
