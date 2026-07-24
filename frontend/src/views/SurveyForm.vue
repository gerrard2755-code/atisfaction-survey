<template>
  <div class="survey-form">
    <h1 class="mb-4">📋 Satisfaction Survey Form</h1>
    
    <form @submit.prevent="submitSurvey" v-if="!submitted">
      <!-- Demographics Section -->
      <div class="card mb-4">
        <div class="card-header">
          <h5>Personal Information</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Gender</label>
              <select v-model="formData.gender" class="form-select">
                <option value="">Select...</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Age Group</label>
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
            <label class="form-label">User Type</label>
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
      <div class="card mb-4" v-for="question in questions" :key="question.id">
        <div class="card-body">
          <p class="mb-3"><strong>{{ question.question }}</strong></p>
          <div class="rating-group">
            <div class="form-check form-check-inline" v-for="score in [1, 2, 3, 4, 5]" :key="score">
              <input 
                class="form-check-input" 
                type="radio" 
                :id="`q${question.id}_${score}`"
                :value="score"
                v-model.number="responses[question.id]"
                required
              >
              <label class="form-check-label" :for="`q${question.id}_${score}`">
                {{ score }} {{ getScoreLabel(score) }}
              </label>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Comments Section -->
      <div class="card mb-4">
        <div class="card-header">
          <h5>Additional Comments</h5>
        </div>
        <div class="card-body">
          <textarea 
            v-model="formData.comment" 
            class="form-control" 
            rows="4" 
            placeholder="Share your suggestions or feedback..."
          ></textarea>
        </div>
      </div>
      
      <button type="submit" class="btn btn-primary btn-lg">Submit Survey</button>
    </form>
    
    <div class="alert alert-success" v-else>
      <h4>✅ Thank you!</h4>
      <p>Your response has been submitted successfully.</p>
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
      submitted: false
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
        { id: 1, question: 'Is the website easy to navigate?' },
        { id: 2, question: 'Is the content relevant and helpful?' },
        { id: 3, question: 'Is the website visually appealing?' },
        { id: 4, question: 'Is the website fast and responsive?' },
        { id: 5, question: 'Overall, are you satisfied with this website?' }
      ]
    },
    getScoreLabel(score) {
      const labels = { 1: 'Poor', 2: 'Fair', 3: 'Good', 4: 'Very Good', 5: 'Excellent' }
      return labels[score] || ''
    },
    async submitSurvey() {
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
        await this.surveyStore.submitResponse(responseData)
        this.submitted = true
      } catch (error) {
        alert('Error submitting survey: ' + (error.message || 'Unknown error'))
      }
    }
  }
}
</script>

<style scoped>
.survey-form {
  max-width: 800px;
  margin: 0 auto;
}

.rating-group {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.form-check {
  margin-bottom: 10px;
}
</style>
