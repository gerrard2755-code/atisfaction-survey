<template>
  <div class="survey-container">
    <div class="survey-header">
      <h1>📋 {{ surveyTitle }}</h1>
      <p class="survey-description">{{ surveyDescription }}</p>
      <div class="progress-bar">
        <div class="progress" :style="{ width: progressPercentage + '%' }"></div>
      </div>
      <small>Step {{ currentStep }} of {{ totalSteps }}</small>
    </div>

    <!-- Alert Messages -->
    <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show">
      <strong>❌ Error:</strong> {{ errorMessage }}
      <button type="button" class="btn-close" @click="errorMessage = ''"></button>
    </div>

    <div v-if="successMessage" class="alert alert-success alert-dismissible fade show">
      <strong>✅ Success:</strong> {{ successMessage }}
      <button type="button" class="btn-close" @click="successMessage = ''"></button>
    </div>

    <form @submit.prevent="submitSurvey" v-if="!submitted && !loading">
      <!-- Demographics Section -->
      <div class="card mb-4" v-show="currentStep === 1">
        <div class="card-header bg-primary text-white">
          <h5>👤 Personal Information</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Gender <span class="text-muted">(Optional)</span></label>
              <select v-model="formData.gender" class="form-select">
                <option value="">Select...</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Age Group <span class="text-muted">(Optional)</span></label>
              <select v-model="formData.age" class="form-select">
                <option value="">Select...</option>
                <option value="under-18">Under 18</option>
                <option value="18-25">18-25</option>
                <option value="26-35">26-35</option>
                <option value="36-50">36-50</option>
                <option value="over-50">Over 50</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">User Type <span class="text-muted">(Optional)</span></label>
            <select v-model="formData.user_type" class="form-select">
              <option value="">Select...</option>
              <option value="visitor">Visitor</option>
              <option value="student">Student</option>
              <option value="staff">Staff</option>
              <option value="teacher">Teacher</option>
              <option value="alumni">Alumni</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Questions Section -->
      <div class="card mb-4" v-show="currentStep === 2">
        <div class="card-header bg-success text-white">
          <h5>⭐ Rate Your Experience</h5>
        </div>
        <div class="card-body">
          <div v-for="(question, index) in questions" :key="question.id" class="mb-4">
            <div class="question-card">
              <div class="question-number">{{ index + 1 }}. </div>
              <p class="question-text"><strong>{{ question.question }}</strong></p>
              <small class="text-muted">Category: {{ question.category }}</small>
              
              <div class="rating-group mt-3">
                <div class="rating-scale">
                  <div v-for="score in [1, 2, 3, 4, 5]" :key="score" class="rating-item">
                    <input 
                      class="form-check-input rating-input" 
                      type="radio" 
                      :id="`q${question.id}_${score}`"
                      :value="score"
                      v-model.number="responses[question.id]"
                      required
                    >
                    <label class="rating-label" :for="`q${question.id}_${score}`" :class="getRatingClass(responses[question.id], score)">
                      <span class="rating-emoji">{{ getEmoji(score) }}</span>
                      <span class="rating-text">{{ getScoreLabel(score) }}</span>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Error message for required questions -->
              <div v-if="!responses[question.id] && showValidationErrors" class="text-danger small mt-2">
                This question is required
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Comments Section -->
      <div class="card mb-4" v-show="currentStep === 3">
        <div class="card-header bg-info text-white">
          <h5>💬 Additional Comments</h5>
        </div>
        <div class="card-body">
          <label class="form-label">Share your feedback <span class="text-muted">(Optional)</span></label>
          <textarea 
            v-model="formData.comment" 
            class="form-control" 
            rows="5" 
            placeholder="Tell us what you think... max 1000 characters"
            maxlength="1000"
          ></textarea>
          <small class="text-muted">{{ formData.comment.length }} / 1000</small>
        </div>
      </div>

      <!-- Review Section -->
      <div class="card mb-4" v-show="currentStep === 4">
        <div class="card-header bg-warning text-dark">
          <h5>📋 Review Your Response</h5>
        </div>
        <div class="card-body">
          <div class="review-section mb-3">
            <h6>Personal Information</h6>
            <p>
              <strong>Gender:</strong> {{ formData.gender || 'Not provided' }}<br>
              <strong>Age:</strong> {{ formData.age || 'Not provided' }}<br>
              <strong>User Type:</strong> {{ formData.user_type || 'Not provided' }}
            </p>
          </div>
          <div class="review-section mb-3">
            <h6>Your Ratings</h6>
            <ul>
              <li v-for="q in questions" :key="q.id">
                {{ q.question }}: <strong>{{ responses[q.id] }}/5 - {{ getScoreLabel(responses[q.id]) }}</strong>
              </li>
            </ul>
          </div>
          <div class="review-section" v-if="formData.comment">
            <h6>Your Comment</h6>
            <p>{{ formData.comment }}</p>
          </div>
        </div>
      </div>

      <!-- Navigation Buttons -->
      <div class="form-navigation">
        <button 
          v-if="currentStep > 1" 
          type="button" 
          @click="previousStep" 
          class="btn btn-secondary me-2"
          :disabled="loading"
        >
          ← Previous
        </button>
        <button 
          v-if="currentStep < totalSteps" 
          type="button" 
          @click="nextStep" 
          class="btn btn-primary"
          :disabled="loading || !isStepValid()"
        >
          Next →
        </button>
        <button 
          v-if="currentStep === totalSteps" 
          type="submit" 
          class="btn btn-success btn-lg"
          :disabled="loading"
        >
          <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
          {{ loading ? 'Submitting...' : '✓ Submit Survey' }}
        </button>
      </div>
    </form>

    <!-- Success Message -->
    <div v-if="submitted" class="alert alert-success text-center">
      <h2>✅ Thank You!</h2>
      <p>Your response has been submitted successfully.</p>
      <p class="text-muted">Reference ID: {{ responseId }}</p>
      <router-link to="/" class="btn btn-primary mt-3">← Back to Home</router-link>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-3">Submitting your response...</p>
    </div>
  </div>
</template>

<script>
import { useSurveyStore } from '../stores/survey'

export default {
  name: 'SurveyForm',
  data() {
    return {
      formData: {
        questionnaire_id: 1,
        gender: '',
        age: '',
        user_type: '',
        comment: ''
      },
      responses: {},
      questions: [],
      submitted: false,
      loading: false,
      errorMessage: '',
      successMessage: '',
      currentStep: 1,
      totalSteps: 4,
      showValidationErrors: false,
      surveyTitle: '📊 Website Satisfaction Survey',
      surveyDescription: 'Help us improve by sharing your feedback',
      responseId: ''
    }
  },
  computed: {
    progressPercentage() {
      return (this.currentStep / this.totalSteps) * 100
    }
  },
  setup() {
    const surveyStore = useSurveyStore()
    return { surveyStore }
  },
  mounted() {
    this.loadQuestions()
  },
  methods: {
    loadQuestions() {
      this.questions = [
        { id: 1, question: 'Is the website easy to navigate?', category: 'usability' },
        { id: 2, question: 'Is the content relevant and helpful?', category: 'content' },
        { id: 3, question: 'Is the website visually appealing?', category: 'design' },
        { id: 4, question: 'Is the website fast and responsive?', category: 'performance' },
        { id: 5, question: 'Overall, are you satisfied with this website?', category: 'content' }
      ]
    },
    getScoreLabel(score) {
      const labels = { 1: 'Poor', 2: 'Fair', 3: 'Good', 4: 'Very Good', 5: 'Excellent' }
      return labels[score] || ''
    },
    getEmoji(score) {
      const emojis = { 1: '😞', 2: '😐', 3: '🙂', 4: '😊', 5: '🤩' }
      return emojis[score] || ''
    },
    getRatingClass(selected, current) {
      if (!selected) return ''
      return selected === current ? 'active' : ''
    },
    isStepValid() {
      if (this.currentStep === 1) return true
      if (this.currentStep === 2) {
        return this.questions.every(q => this.responses[q.id])
      }
      if (this.currentStep === 3) return true
      if (this.currentStep === 4) return true
      return false
    },
    nextStep() {
      if (this.currentStep === 2 && !this.isStepValid()) {
        this.showValidationErrors = true
        this.errorMessage = 'Please answer all questions before proceeding'
        return
      }
      if (this.currentStep < this.totalSteps) {
        this.currentStep++
        this.showValidationErrors = false
      }
    },
    previousStep() {
      if (this.currentStep > 1) {
        this.currentStep--
        this.showValidationErrors = false
      }
    },
    async submitSurvey() {
      this.errorMessage = ''
      this.showValidationErrors = false

      if (!this.isStepValid()) {
        this.errorMessage = 'Please complete all required fields'
        return
      }

      this.loading = true

      const responseData = {
        questionnaire_id: this.formData.questionnaire_id,
        gender: this.formData.gender || null,
        age: this.formData.age || null,
        user_type: this.formData.user_type || null,
        comment: this.formData.comment || null,
        responses: Object.entries(this.responses).map(([qId, score]) => ({
          question_id: parseInt(qId),
          score: score
        }))
      }

      try {
        const response = await this.surveyStore.submitResponse(responseData)
        this.responseId = response.data?.response_id || 'N/A'
        this.submitted = true
        this.successMessage = 'Thank you for completing the survey!'
      } catch (error) {
        console.error('Error:', error)
        this.errorMessage = error.response?.data?.message || 
                          error.message || 
                          'An error occurred while submitting the survey. Please try again.'
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
.survey-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

.survey-header {
  margin-bottom: 30px;
  text-align: center;
}

.survey-header h1 {
  font-size: 2.5em;
  margin-bottom: 10px;
  color: #333;
}

.survey-description {
  font-size: 1.1em;
  color: #666;
  margin-bottom: 20px;
}

.progress-bar {
  background-color: #e9ecef;
  height: 10px;
  border-radius: 10px;
  margin-bottom: 10px;
  overflow: hidden;
}

.progress {
  background: linear-gradient(90deg, #007bff, #0056b3);
  height: 100%;
  transition: width 0.3s ease;
}

.question-card {
  padding: 20px;
  border: 2px solid #f0f0f0;
  border-radius: 8px;
  background-color: #fafafa;
  transition: all 0.3s ease;
}

.question-card:hover {
  border-color: #007bff;
  background-color: #f8f9ff;
}

.question-number {
  color: #007bff;
  font-weight: bold;
}

.question-text {
  margin: 10px 0;
  color: #333;
}

.rating-scale {
  display: flex;
  justify-content: space-around;
  gap: 10px;
  flex-wrap: wrap;
}

.rating-item {
  flex: 0 1 calc(20% - 10px);
  min-width: 80px;
}

.rating-input {
  display: none;
}

.rating-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 15px 10px;
  border: 2px solid #ddd;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  background-color: white;
}

.rating-label:hover {
  border-color: #007bff;
  background-color: #f0f8ff;
  transform: scale(1.05);
}

.rating-label.active {
  border-color: #28a745;
  background-color: #e8f5e9;
  font-weight: bold;
}

.rating-emoji {
  font-size: 1.8em;
  margin-bottom: 5px;
}

.rating-text {
  font-size: 0.85em;
  text-align: center;
  color: #666;
}

.rating-label.active .rating-text {
  color: #28a745;
}

.form-navigation {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-top: 30px;
}

.form-navigation button {
  min-width: 120px;
}

.review-section {
  padding: 15px;
  background-color: #f8f9fa;
  border-left: 4px solid #007bff;
  border-radius: 4px;
}

.review-section h6 {
  color: #007bff;
  margin-bottom: 10px;
  font-weight: bold;
}

.review-section ul {
  list-style: none;
  padding: 0;
}

.review-section li {
  padding: 8px 0;
  border-bottom: 1px solid #ddd;
}

.review-section li:last-child {
  border-bottom: none;
}

alert {
  margin-bottom: 20px;
}

@media (max-width: 768px) {
  .rating-scale {
    flex-direction: column;
  }

  .rating-item {
    flex: 0 1 100%;
    min-width: auto;
  }

  .form-navigation {
    flex-direction: column;
  }

  .form-navigation button {
    width: 100%;
  }
}
</style>
