document.addEventListener("DOMContentLoaded", function () {
    const addTaskForm = document.getElementById("addTaskForm");
    const taskInput = document.getElementById("taskInput");
    const todoList = document.getElementById("todo");
    const doneList = document.getElementById("done");

    const baseURL = "http://projects.local:8888/api/";

    let tasks = []; // Array to store tasks

    addTaskForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const taskName = taskInput.value.trim();

        if (taskName === "") {
            showError("Please enter a task name.");
            return;
        }

        const newTask = { name: taskName, done: false };
        createTask(newTask)
            .then(task => {
                tasks.push(task);
                const taskElement = createTaskElement(task);
                todoList.appendChild(taskElement);
                taskInput.value = "";
            })
            .catch(error => {
                showError("Failed to create task.");
                console.error(error);
            });
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
        return fetch(baseURL + "task", {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(taskData),
        })
            .then(response => response.json())
            .then(updatedTask => {
                const taskIndex = tasks.findIndex(task => task.id === taskId);
                if (taskIndex !== -1) {
                    tasks[taskIndex] = updatedTask;
                    saveTasks();
                }
                return updatedTask;
            });
    }

    function deleteTask(taskId) {
        return fetch(baseURL + "task?id=" + taskId, {
            method: "DELETE",
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Failed to delete task.");
                }
                tasks = tasks.filter(task => task.id !== taskId);
                saveTasks();
            });
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
            deleteTask(task.id)
                .then(() => {
                    li.remove();
                })
                .catch(error => {
                    showError("Failed to delete task.");
                    console.error(error);
                });
        });
        li.appendChild(deleteButton);

        const undoButton = document.createElement("button");
        undoButton.textContent = "Ongedaan maken";
        undoButton.classList.add("button_undo");
        undoButton.addEventListener("click", function () {
            updateTask(task.id, { done: false })
                .then(updatedTask => {
                    todoList.appendChild(li);
                    li.removeChild(undoButton);
                    li.appendChild(doneButton);
                })
                .catch(error => {
                    showError("Failed to update task.");
                    console.error(error);
                });
        });

        const doneButton = document.createElement("button");
        doneButton.textContent = "Gedaan";
        doneButton.classList.add("button_done");
        doneButton.addEventListener("click", function () {
            updateTask(task.id, { done: true })
                .then(updatedTask => {
                    doneList.appendChild(li);
                    li.removeChild(doneButton);
                    li.appendChild(undoButton);
                })
                .catch(error => {
                    showError("Failed to update task.");
                    console.error(error);
                });
        });

        if (task.done) {
            li.appendChild(undoButton);
        } else {
            li.appendChild(doneButton);
        }

        return li;
    }

    function createTask(taskData) {
        return fetch(baseURL + "task", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(taskData),
        })
            .then(response => response.json())
            .catch(error => {
                throw new Error("Failed to create task.");
            });
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
