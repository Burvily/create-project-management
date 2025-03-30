<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Create New Project</h1>
      <router-link to="/projects" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
        Cancel
      </router-link>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
      <form @submit.prevent="submitForm" class="space-y-6">
        <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
          <span class="block sm:inline">{{ error }}</span>
        </div>

        <!-- Basic Information -->
        <div class="space-y-4">
          <h2 class="text-xl font-semibold border-b pb-2">Basic Information</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Project Name*</label>
              <input
                id="name"
                v-model="project.name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div>
              <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Project Code</label>
              <input
                id="code"
                v-model="project.code"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>
          
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              id="description"
              v-model="project.description"
              rows="4"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>
          </div>
        </div>

        <!-- Timeline -->
        <div class="space-y-4">
          <h2 class="text-xl font-semibold border-b pb-2">Timeline</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date*</label>
              <input
                id="start_date"
                v-model="project.start_date"
                type="date"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div>
              <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date*</label>
              <input
                id="end_date"
                v-model="project.end_date"
                type="date"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>
        </div>

        <!-- Budget -->
        <div class="space-y-4">
          <h2 class="text-xl font-semibold border-b pb-2">Budget</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Total Budget</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <span class="text-gray-500">$</span>
                </div>
                <input
                  id="budget"
                  v-model="project.budget"
                  type="number"
                  min="0"
                  step="0.01"
                  class="w-full pl-7 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
            
            <div>
              <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
              <select
                id="currency"
                v-model="project.currency"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="USD">USD - US Dollar</option>
                <option value="EUR">EUR - Euro</option>
                <option value="GBP">GBP - British Pound</option>
                <option value="CAD">CAD - Canadian Dollar</option>
                <option value="AUD">AUD - Australian Dollar</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Project Details -->
        <div class="space-y-4">
          <h2 class="text-xl font-semibold border-b pb-2">Project Details</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                id="status"
                v-model="project.status"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="planning">Planning</option>
                <option value="active">Active</option>
                <option value="on_hold">On Hold</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            
            <div>
              <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
              <select
                id="priority"
                v-model="project.priority"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
              </select>
            </div>
          </div>
          
          <div>
            <label for="objectives" class="block text-sm font-medium text-gray-700 mb-1">Project Objectives</label>
            <textarea
              id="objectives"
              v-model="project.objectives"
              rows="4"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
          <button
            type="submit"
            class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
            :disabled="isSubmitting"
          >
            <span v-if="isSubmitting">Creating...</span>
            <span v-else>Create Project</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'NewProjectView',
  data() {
    return {
      project: {
        name: '',
        code: '',
        description: '',
        start_date: '',
        end_date: '',
        budget: null,
        currency: 'USD',
        status: 'planning',
        priority: 'medium',
        objectives: ''
      },
      isSubmitting: false,
      error: null
    };
  },
  methods: {
    async submitForm() {
      this.isSubmitting = true;
      this.error = null;
      
      try {
        // Validate dates
        if (new Date(this.project.end_date) < new Date(this.project.start_date)) {
          throw new Error('End date cannot be before start date');
        }
        
        const response = await axios.post('/api/projects', this.project);
        
        // Show success notification
        this.$toast.success('Project created successfully!');
        
        // Redirect to the project detail page
        this.$router.push(`/projects/${response.data.id}`);
      } catch (error) {
        console.error('Error creating project:', error);
        
        if (error.response && error.response.data && error.response.data.message) {
          this.error = error.response.data.message;
        } else if (error.message) {
          this.error = error.message;
        } else {
          this.error = 'An error occurred while creating the project. Please try again.';
        }
      } finally {
        this.isSubmitting = false;
      }
    }
  }
};
</script>

