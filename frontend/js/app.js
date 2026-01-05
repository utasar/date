// Date App - Frontend JavaScript

const API_BASE = '../backend';
let currentLocation = 'Your City';

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('Date App loaded.');
    initializeApp();
});

function initializeApp() {
    setupNavigation();
    loadDashboard();
    setupEventListeners();
}

// Navigation
function setupNavigation() {
    const navButtons = document.querySelectorAll('.nav-btn');
    
    navButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.dataset.section;
            switchSection(section);
        });
    });
}

function switchSection(sectionId) {
    // Update active nav button
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.section === sectionId) {
            btn.classList.add('active');
        }
    });
    
    // Update active section
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    document.getElementById(sectionId).classList.add('active');
    
    // Load section-specific data
    loadSectionData(sectionId);
}

function loadSectionData(sectionId) {
    switch(sectionId) {
        case 'dashboard':
            loadDashboard();
            break;
        case 'schedule':
            loadSchedule();
            break;
        case 'activities':
            loadActivities();
            loadExerciseSuggestions();
            break;
        case 'health':
            loadCalorieGuide();
            loadFoodSuggestions();
            loadHealthPlan();
            loadDietitians();
            break;
        case 'goals':
            loadGoals();
            loadTodayGoals();
            loadUpcomingGoals();
            break;
        case 'locations':
            loadGyms();
            loadFitnessResources();
            loadRestaurants();
            break;
    }
}

// Dashboard
async function loadDashboard() {
    await Promise.all([
        loadDashboardSchedule(),
        loadDashboardGoals(),
        loadDashboardActivities(),
        loadDashboardProgress()
    ]);
}

async function loadDashboardSchedule() {
    try {
        const data = await fetchAPI('schedule');
        const container = document.getElementById('dashboard-schedule');
        
        if (data.suggestions && data.suggestions.length > 0) {
            container.innerHTML = data.suggestions.slice(0, 3).map(s => `
                <div class="suggestion-item">
                    <strong>${s.time} - ${s.title}</strong>
                    <p>${s.description}</p>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p>No suggestions for today</p>';
        }
    } catch (error) {
        console.error('Error loading dashboard schedule:', error);
        document.getElementById('dashboard-schedule').innerHTML = '<p>Unable to load schedule</p>';
    }
}

async function loadDashboardGoals() {
    try {
        const data = await fetchAPI('goals/today');
        const container = document.getElementById('dashboard-goals');
        
        if (data.goals && data.goals.length > 0) {
            const incomplete = data.goals.filter(g => !g.completed);
            container.innerHTML = incomplete.slice(0, 3).map(g => `
                <div class="goal-item">
                    <strong>${g.title}</strong>
                    <span class="badge badge-${g.priority}">${g.priority}</span>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p>No goals for today</p>';
        }
    } catch (error) {
        console.error('Error loading dashboard goals:', error);
        document.getElementById('dashboard-goals').innerHTML = '<p>Unable to load goals</p>';
    }
}

async function loadDashboardActivities() {
    try {
        const data = await fetchAPI('activities');
        const container = document.getElementById('dashboard-activities');
        
        if (data.activities && data.activities.length > 0) {
            const completed = data.activities.filter(a => a.completed);
            container.innerHTML = `
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value">${completed.length}</div>
                        <div class="stat-label">Completed</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${data.totalCaloriesBurned || 0}</div>
                        <div class="stat-label">Calories</div>
                    </div>
                </div>
            `;
        } else {
            container.innerHTML = '<p>No activities logged today</p>';
        }
    } catch (error) {
        console.error('Error loading dashboard activities:', error);
        document.getElementById('dashboard-activities').innerHTML = '<p>Unable to load activities</p>';
    }
}

async function loadDashboardProgress() {
    try {
        const data = await fetchAPI('goals');
        const container = document.getElementById('dashboard-progress');
        
        if (data.goals && data.goals.length > 0) {
            const avgProgress = Math.round(data.goals.reduce((sum, g) => sum + g.progress, 0) / data.goals.length);
            container.innerHTML = `
                <div class="stat-item">
                    <div class="stat-value">${avgProgress}%</div>
                    <div class="stat-label">Average Progress</div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: ${avgProgress}%"></div>
                </div>
            `;
        } else {
            container.innerHTML = '<p>Set goals to track progress</p>';
        }
    } catch (error) {
        console.error('Error loading dashboard progress:', error);
        document.getElementById('dashboard-progress').innerHTML = '<p>Unable to load progress</p>';
    }
}

// Schedule
async function loadSchedule() {
    try {
        const data = await fetchAPI('schedule');
        
        // Load tasks
        const tasksContainer = document.getElementById('schedule-tasks');
        if (data.tasks && data.tasks.length > 0) {
            tasksContainer.innerHTML = data.tasks.map(task => `
                <div class="activity-item">
                    <h4>${task.title}</h4>
                    <p>${task.description}</p>
                </div>
            `).join('');
        } else {
            tasksContainer.innerHTML = '<p>No tasks scheduled for today. Add some tasks to stay organized!</p>';
        }
        
        // Load suggestions
        const suggestionsContainer = document.getElementById('schedule-suggestions');
        if (data.suggestions && data.suggestions.length > 0) {
            suggestionsContainer.innerHTML = data.suggestions.map(s => `
                <div class="suggestion-item">
                    <span class="badge badge-${s.type}">${s.type}</span>
                    <h4>${s.time} - ${s.title}</h4>
                    <p>${s.description}</p>
                    ${s.duration ? `<p><strong>Duration:</strong> ${s.duration} minutes</p>` : ''}
                    ${s.calories ? `<p><strong>Calories:</strong> ${s.calories}</p>` : ''}
                </div>
            `).join('');
        } else {
            suggestionsContainer.innerHTML = '<p>No suggestions available at this time</p>';
        }
    } catch (error) {
        console.error('Error loading schedule:', error);
    }
}

// Activities
async function loadActivities() {
    try {
        const data = await fetchAPI('activities');
        const container = document.getElementById('activities-list');
        
        if (data.activities && data.activities.length > 0) {
            container.innerHTML = `
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value">${data.totalDuration || 0}</div>
                        <div class="stat-label">Minutes</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${data.totalCaloriesBurned || 0}</div>
                        <div class="stat-label">Calories</div>
                    </div>
                </div>
                ${data.activities.map(a => `
                    <div class="activity-item">
                        <h4>${a.type} ${a.completed ? '✓' : ''}</h4>
                        <p>Duration: ${a.duration} minutes | Calories: ${a.caloriesBurned}</p>
                    </div>
                `).join('')}
            `;
        } else {
            container.innerHTML = '<p>No activities logged today. Start tracking your workouts!</p>';
        }
    } catch (error) {
        console.error('Error loading activities:', error);
        document.getElementById('activities-list').innerHTML = '<p>Unable to load activities</p>';
    }
}

async function loadExerciseSuggestions() {
    const level = document.getElementById('fitness-level').value;
    try {
        const exercises = await fetchAPI(`activities/exercises?fitnessLevel=${level}`);
        const container = document.getElementById('exercise-suggestions');
        
        if (exercises && exercises.length > 0) {
            container.innerHTML = exercises.map(e => `
                <div class="activity-item">
                    <h4>${e.name}</h4>
                    <p>${e.instructions}</p>
                    <p><strong>Duration:</strong> ${e.duration} min | <strong>Intensity:</strong> ${e.intensity} | <strong>Calories/min:</strong> ${e.caloriesPerMin}</p>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading exercise suggestions:', error);
    }
}

// Health & Nutrition
async function loadCalorieGuide() {
    const activityLevel = document.getElementById('activity-level').value;
    try {
        const data = await fetchAPI(`health/calories?activityLevel=${activityLevel}`);
        const container = document.getElementById('calorie-guide');
        
        container.innerHTML = `
            <div class="stat-item">
                <div class="stat-value">${data.dailyCalories}</div>
                <div class="stat-label">Daily Calories</div>
            </div>
            <h4 style="margin-top: 1rem;">Meal Breakdown</h4>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">${data.breakdown.breakfast}</div>
                    <div class="stat-label">Breakfast</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">${data.breakdown.lunch}</div>
                    <div class="stat-label">Lunch</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">${data.breakdown.dinner}</div>
                    <div class="stat-label">Dinner</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">${data.breakdown.snacks}</div>
                    <div class="stat-label">Snacks</div>
                </div>
            </div>
            <h4 style="margin-top: 1rem;">Macros (grams/day)</h4>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">${data.macros.protein}g</div>
                    <div class="stat-label">Protein</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">${data.macros.carbs}g</div>
                    <div class="stat-label">Carbs</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">${data.macros.fats}g</div>
                    <div class="stat-label">Fats</div>
                </div>
            </div>
        `;
    } catch (error) {
        console.error('Error loading calorie guide:', error);
    }
}

async function loadFoodSuggestions() {
    const healthGoal = document.getElementById('health-goal').value;
    try {
        const data = await fetchAPI(`health/food-suggestions?healthGoal=${healthGoal}`);
        const container = document.getElementById('food-suggestions');
        
        let html = '';
        for (const [goal, meals] of Object.entries(data)) {
            html += `<h4>${goal.replace('_', ' ').toUpperCase()}</h4>`;
            for (const [mealType, foods] of Object.entries(meals)) {
                html += `
                    <div class="suggestion-item">
                        <strong>${mealType.charAt(0).toUpperCase() + mealType.slice(1)}</strong>
                        <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                            ${foods.map(food => `<li>${food}</li>`).join('')}
                        </ul>
                    </div>
                `;
            }
        }
        container.innerHTML = html;
    } catch (error) {
        console.error('Error loading food suggestions:', error);
    }
}

async function loadHealthPlan() {
    const goal = document.getElementById('plan-goal').value;
    try {
        const data = await fetchAPI(`health/plan?goal=${goal}`);
        const container = document.getElementById('health-plan');
        
        container.innerHTML = `
            <p><strong>Goal:</strong> ${data.goal.replace('_', ' ').toUpperCase()}</p>
            <p><strong>Duration:</strong> ${data.duration}</p>
            <div style="margin-top: 1rem;">
                ${data.steps.map(week => `
                    <div class="plan-week">
                        <h4>Week ${week.week}: ${week.title}</h4>
                        <ul>
                            ${week.tasks.map(task => `<li>${task}</li>`).join('')}
                        </ul>
                    </div>
                `).join('')}
            </div>
        `;
    } catch (error) {
        console.error('Error loading health plan:', error);
    }
}

async function loadDietitians() {
    try {
        const data = await fetchAPI(`health/dietitians?location=${currentLocation}`);
        const container = document.getElementById('dietitian-list');
        
        if (data.dietitians && data.dietitians.length > 0) {
            container.innerHTML = data.dietitians.map(d => `
                <div class="location-item">
                    <h4>${d.name}</h4>
                    <div class="location-details">
                        <span>📍 ${d.distance}</span>
                        <span class="rating">⭐ ${d.rating}</span>
                        <span>${d.specialization}</span>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading dietitians:', error);
    }
}

// Goals
async function loadGoals() {
    try {
        const data = await fetchAPI('goals');
        const container = document.getElementById('goals-list');
        
        if (data.goals && data.goals.length > 0) {
            container.innerHTML = data.goals.map(g => `
                <div class="goal-item">
                    <h4>${g.title}</h4>
                    <p>${g.description}</p>
                    <p><strong>Target Date:</strong> ${g.targetDate}</p>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: ${g.progress}%"></div>
                    </div>
                    <p style="text-align: center; margin-top: 0.5rem;">${g.progress}% Complete</p>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p>No active goals. Create your first goal above!</p>';
        }
    } catch (error) {
        console.error('Error loading goals:', error);
    }
}

async function loadTodayGoals() {
    try {
        const data = await fetchAPI('goals/today');
        const container = document.getElementById('today-goals');
        
        if (data.goals && data.goals.length > 0) {
            container.innerHTML = data.goals.map(g => `
                <div class="goal-item">
                    <strong>${g.completed ? '✓' : '○'} ${g.title}</strong>
                    <span class="badge badge-${g.priority}">${g.priority}</span>
                    <p>${g.type}</p>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p>No goals set for today</p>';
        }
    } catch (error) {
        console.error('Error loading today goals:', error);
    }
}

async function loadUpcomingGoals() {
    try {
        const data = await fetchAPI('goals/upcoming?days=7');
        const container = document.getElementById('upcoming-goals');
        
        if (data.upcoming && data.upcoming.length > 0) {
            container.innerHTML = data.upcoming.slice(0, 3).map(day => `
                <div class="suggestion-item">
                    <strong>${day.date}</strong>
                    ${day.goals.map(g => `<p>• ${g.title} (${g.estimatedDuration} min)</p>`).join('')}
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading upcoming goals:', error);
    }
}

// Locations
async function loadGyms() {
    try {
        const data = await fetchAPI(`location/gyms?location=${currentLocation}`);
        const container = document.getElementById('gyms-list');
        
        if (data.gyms && data.gyms.length > 0) {
            container.innerHTML = data.gyms.map(gym => `
                <div class="location-item">
                    <h4>${gym.name}</h4>
                    <p>${gym.address}</p>
                    <div class="location-details">
                        <span>📍 ${gym.distance}</span>
                        <span class="rating">⭐ ${gym.rating}</span>
                        <span>💵 ${gym.pricing}</span>
                        <span>🕐 ${gym.hours}</span>
                    </div>
                    <p style="margin-top: 0.5rem;"><strong>Amenities:</strong> ${gym.amenities.join(', ')}</p>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading gyms:', error);
    }
}

async function loadFitnessResources() {
    try {
        const data = await fetchAPI(`location/resources?location=${currentLocation}`);
        const container = document.getElementById('resources-list');
        
        let html = '';
        
        if (data.personal_trainers) {
            html += '<h4>Personal Trainers</h4>';
            html += data.personal_trainers.map(pt => `
                <div class="resource-item">
                    <h4>${pt.name}</h4>
                    <p>${pt.specialization} | ${pt.experience} experience</p>
                    <div class="location-details">
                        <span class="rating">⭐ ${pt.rating}</span>
                        <span>💵 ${pt.pricing}</span>
                    </div>
                </div>
            `).join('');
        }
        
        if (data.yoga_studios) {
            html += '<h4 style="margin-top: 1rem;">Yoga Studios</h4>';
            html += data.yoga_studios.map(ys => `
                <div class="resource-item">
                    <h4>${ys.name}</h4>
                    <p>${ys.style}</p>
                    <div class="location-details">
                        <span class="rating">⭐ ${ys.rating}</span>
                        <span>💵 ${ys.pricing}</span>
                    </div>
                </div>
            `).join('');
        }
        
        if (data.sports_clubs) {
            html += '<h4 style="margin-top: 1rem;">Sports Clubs</h4>';
            html += data.sports_clubs.map(sc => `
                <div class="resource-item">
                    <h4>${sc.name}</h4>
                    <p>Activities: ${sc.activities.join(', ')}</p>
                    <div class="location-details">
                        <span class="rating">⭐ ${sc.rating}</span>
                        <span>💵 ${sc.pricing}</span>
                    </div>
                </div>
            `).join('');
        }
        
        if (data.running_groups) {
            html += '<h4 style="margin-top: 1rem;">Running Groups</h4>';
            html += data.running_groups.map(rg => `
                <div class="resource-item">
                    <h4>${rg.name}</h4>
                    <p>${rg.meetingTime} | ${rg.level}</p>
                    <div class="location-details">
                        <span>💵 ${rg.pricing}</span>
                    </div>
                </div>
            `).join('');
        }
        
        container.innerHTML = html;
    } catch (error) {
        console.error('Error loading fitness resources:', error);
    }
}

async function loadRestaurants() {
    try {
        const data = await fetchAPI(`location/restaurants?location=${currentLocation}`);
        const container = document.getElementById('restaurants-list');
        
        if (data.restaurants && data.restaurants.length > 0) {
            container.innerHTML = data.restaurants.map(r => `
                <div class="location-item">
                    <h4>${r.name}</h4>
                    <p>${r.cuisine}</p>
                    <div class="location-details">
                        <span>📍 ${r.distance}</span>
                        <span class="rating">⭐ ${r.rating}</span>
                        <span>💵 ${r.priceRange}</span>
                    </div>
                    <p style="margin-top: 0.5rem;"><strong>Specialties:</strong> ${r.specialties.join(', ')}</p>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading restaurants:', error);
    }
}

// Event Listeners
function setupEventListeners() {
    // Activity form
    const activityForm = document.getElementById('activity-form');
    if (activityForm) {
        activityForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const type = document.getElementById('activity-type').value;
            const duration = document.getElementById('activity-duration').value;
            
            try {
                await fetchAPI('activities', 'POST', {
                    type: type,
                    duration: parseInt(duration),
                    date: new Date().toISOString().split('T')[0]
                });
                
                alert('Activity logged successfully!');
                activityForm.reset();
                loadActivities();
                loadDashboardActivities();
            } catch (error) {
                alert('Error logging activity. Please try again.');
            }
        });
    }
    
    // Goal form
    const goalForm = document.getElementById('goal-form');
    if (goalForm) {
        goalForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const title = document.getElementById('goal-title').value;
            const description = document.getElementById('goal-description').value;
            const targetDate = document.getElementById('goal-target-date').value;
            
            try {
                await fetchAPI('goals', 'POST', {
                    title: title,
                    description: description,
                    targetDate: targetDate
                });
                
                alert('Goal created successfully!');
                goalForm.reset();
                loadGoals();
            } catch (error) {
                alert('Error creating goal. Please try again.');
            }
        });
    }
    
    // Fitness level change
    const fitnessLevel = document.getElementById('fitness-level');
    if (fitnessLevel) {
        fitnessLevel.addEventListener('change', loadExerciseSuggestions);
    }
    
    // Activity level change
    const activityLevel = document.getElementById('activity-level');
    if (activityLevel) {
        activityLevel.addEventListener('change', loadCalorieGuide);
    }
    
    // Health goal change
    const healthGoal = document.getElementById('health-goal');
    if (healthGoal) {
        healthGoal.addEventListener('change', loadFoodSuggestions);
    }
    
    // Plan goal change
    const planGoal = document.getElementById('plan-goal');
    if (planGoal) {
        planGoal.addEventListener('change', loadHealthPlan);
    }
}

// Update location function (global)
window.updateLocation = function() {
    const locationInput = document.getElementById('user-location');
    currentLocation = locationInput.value || 'Your City';
    loadGyms();
    loadFitnessResources();
    loadRestaurants();
    loadDietitians();
};

// API Helper
async function fetchAPI(endpoint, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }
    
    const url = endpoint.includes('?') 
        ? `${API_BASE}/${endpoint}`
        : `${API_BASE}/${endpoint}${method === 'GET' && data ? '?' + new URLSearchParams(data) : ''}`;
    
    const response = await fetch(url, options);
    
    if (!response.ok) {
        throw new Error(`API request failed: ${response.statusText}`);
    }
    
    return await response.json();
}