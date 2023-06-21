document.addEventListener("DOMContentLoaded", function () {
    const addTaskForm = document.getElementById("addTaskForm");
    const taskInput = document.getElementById("taskInput");
    const todoList = document.getElementById("todo");
    const doneList = document.getElementById("done");

    let tasks = []; // Array to store tasks

    addTaskForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const taskName = taskInput.value.trim();

        if (taskName === "") {
            showError("Please enter a task name.");
            return;
        }

        const newTask = { id: generateTaskId(), name: taskName, done: false };
        tasks.push(newTask);
        saveTasks();

        const taskElement = createTaskElement(newTask);
        todoList.appendChild(taskElement);

        taskInput.value = "";
    });

    function generateTaskId() {
        return Date.now().toString(); // Simple implementation to generate a unique ID
    }

    function saveTasks() {
        // Store tasks in browser's local storage
        localStorage.setItem("tasks", JSON.stringify(tasks));
    }

    function loadTasks() {
        // Load tasks from local storage
        const savedTasks = localStorage.getItem("tasks");
        if (savedTasks) {
            tasks = JSON.parse(savedTasks);
            tasks.forEach(task => {
                const taskElement = createTaskElement(task);
                if (task.done) {
                    doneList.appendChild(taskElement);
                } else {
                    todoList.appendChild(taskElement);
                }
            });
        }
    }

    function updateTask(taskId, taskData) {
        const taskIndex = tasks.findIndex(task => task.id === taskId);
        if (taskIndex !== -1) {
            tasks[taskIndex] = { ...tasks[taskIndex], ...taskData };
            saveTasks();
        }
    }

    function deleteTask(taskId) {
        tasks = tasks.filter(task => task.id !== taskId);
        saveTasks();
    }

    function createTaskElement(task) {
        const li = document.createElement("li");
        li.id = `task-${task.id}`;

        const span = document.createElement("span");
        span.textContent = task.name;
        li.appendChild(span);

        const deleteButton = document.createElement("button");
        deleteButton.textContent = "Verwijderen";
        deleteButton.classList.add("button_delete");
        deleteButton.addEventListener("click", function () {
            deleteTask(task.id);
            li.remove();
        });
        li.appendChild(deleteButton);

        const undoButton = document.createElement("button");
        undoButton.textContent = "Ongedaan maken";
        undoButton.classList.add("button_undo");
        undoButton.addEventListener("click", function () {
            updateTask(task.id, { done: false });
            todoList.appendChild(li);
            li.removeChild(undoButton);
            li.appendChild(doneButton);
        });

        const doneButton = document.createElement("button");
        doneButton.textContent = "Gedaan";
        doneButton.classList.add("button_done");
        doneButton.addEventListener("click", function () {
            updateTask(task.id, { done: true });
            doneList.appendChild(li);
            li.removeChild(doneButton);
            li.appendChild(undoButton);
        });

        if (task.done) {
            li.appendChild(undoButton);
        } else {
            li.appendChild(doneButton);
        }

        return li;
    }


    function showError(errorMessage) {
        const errorElement = document.createElement("p");
        errorElement.textContent = errorMessage;
        errorElement.style.color = "red";
        addTaskForm.appendChild(errorElement);

        setTimeout(function () {
            errorElement.remove();
        }, 2000);
    }

    // Load tasks on page load
    loadTasks();
});
