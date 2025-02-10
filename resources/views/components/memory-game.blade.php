<div class="bg-white p-6 rounded-lg shadow-md">
    <!-- Add audio elements -->
    <audio id="correctSound" preload="auto">
        <source src="{{ asset('sounds/correct.mp3') }}" type="audio/mp3">
    </audio>
    <audio id="wrongSound" preload="auto">
        <source src="{{ asset('sounds/wrong.mp3') }}" type="audio/mp3">
    </audio>
    <audio id="switchSound" preload="auto">
        <source src="{{ asset('sounds/switch.mp3') }}" type="audio/mp3">
    </audio>

    <div x-data="wordGame()" x-init="init()" class="w-full">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Word Scramble</h2>
            <div class="flex gap-4">
                <span>Score: <span x-text="score"></span></span>
                <!-- Add sound toggle button -->
                <button @click="toggleSound" class="ml-4">
                    <span x-show="soundEnabled" class="text-xl">🔊</span>
                    <span x-show="!soundEnabled" class="text-xl">🔇</span>
                </button>
            </div>
        </div>

        <div class="mb-4">
            <select 
                x-model="selectedCategory" 
                @change="changeCategory()"
                class="w-full p-2 border rounded-lg"
            >
                <template x-for="category in categories" :key="category.id">
                    <option 
                        :value="category.id" 
                        x-text="category.name"
                    ></option>
                </template>
            </select>
        </div>

        <div class="text-center mb-8">
            <div class="text-3xl font-bold mb-4" x-text="scrambledWord"></div>
            
            <input 
                type="text" 
                x-model="userGuess" 
                @keyup.enter="checkGuess"
                class="border-2 border-gray-300 rounded-lg px-4 py-2 mb-4 w-full text-center text-xl"
                placeholder="Type your answer..."
            >

            <button 
                @click="checkGuess"
                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors"
            >
                Submit
            </button>

            <button 
                @click="newWord"
                class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors ml-2"
            >
                Skip
            </button>
        </div>

        <div x-show="message" class="text-center mb-4">
            <p x-text="message" :class="{
                'text-green-600': isCorrect,
                'text-red-600': !isCorrect && message
            }" class="text-lg font-bold"></p>
        </div>

        <div class="text-center text-gray-600">
            <p>Hint: <span x-text="currentHint"></span></p>
        </div>
    </div>
</div>

<script>
function wordGame() {
    return {
        categories: [
            {
                id: 'academic',
                name: 'Academic',
                words: [
                    { word: 'LEARNING', hint: 'Process of acquiring knowledge' },
                    { word: 'STUDENT', hint: 'Person who studies' },
                    { word: 'EDUCATION', hint: 'Process of teaching and learning' },
                    { word: 'KNOWLEDGE', hint: 'Information and skills acquired' },
                    { word: 'UNIVERSITY', hint: 'Higher education institution' },
                    { word: 'RESEARCH', hint: 'Systematic investigation' },
                    { word: 'ACADEMIC', hint: 'Related to education and scholarship' },
                    { word: 'STUDY', hint: 'Detailed examination of a subject' },
                    { word: 'LECTURE', hint: 'Educational speech' },
                    { word: 'CAMPUS', hint: 'University grounds' }
                ]
            },
            {
                id: 'technology',
                name: 'Technology',
                words: [
                    { word: 'COMPUTER', hint: 'Electronic device for processing data' },
                    { word: 'INTERNET', hint: 'Global computer network' },
                    { word: 'SOFTWARE', hint: 'Computer programs' },
                    { word: 'HARDWARE', hint: 'Physical components of a computer' },
                    { word: 'DATABASE', hint: 'Organized collection of data' },
                    { word: 'NETWORK', hint: 'Connected computers' },
                    { word: 'PROGRAMMING', hint: 'Writing computer code' },
                    { word: 'ALGORITHM', hint: 'Step-by-step problem solving' }
                ]
            },
            {
                id: 'science',
                name: 'Science',
                words: [
                    { word: 'EXPERIMENT', hint: 'Scientific test' },
                    { word: 'HYPOTHESIS', hint: 'Scientific assumption' },
                    { word: 'CHEMISTRY', hint: 'Study of matter' },
                    { word: 'BIOLOGY', hint: 'Study of life' },
                    { word: 'PHYSICS', hint: 'Study of matter and energy' },
                    { word: 'MOLECULE', hint: 'Group of atoms' },
                    { word: 'ELEMENT', hint: 'Pure chemical substance' },
                    { word: 'THEORY', hint: 'Scientific explanation' }
                ]
            },
            {
                id: 'mathematics',
                name: 'Mathematics',
                words: [
                    { word: 'ALGEBRA', hint: 'Branch of mathematics' },
                    { word: 'GEOMETRY', hint: 'Study of shapes' },
                    { word: 'CALCULUS', hint: 'Advanced mathematics' },
                    { word: 'EQUATION', hint: 'Mathematical statement' },
                    { word: 'FRACTION', hint: 'Part of a whole' },
                    { word: 'DECIMAL', hint: 'Base-10 number' },
                    { word: 'TRIANGLE', hint: 'Three-sided shape' },
                    { word: 'FORMULA', hint: 'Mathematical rule' }
                ]
            }
        ],
        selectedCategory: 'academic',
        currentWord: '',
        currentHint: '',
        scrambledWord: '',
        userGuess: '',
        score: 0,
        message: '',
        isCorrect: false,
        soundEnabled: true,

        init() {
            this.newWord();
        },

        changeCategory() {
            this.playSound('switchSound');
            this.newWord();
        },

        getCurrentCategoryWords() {
            return this.categories.find(c => c.id === this.selectedCategory).words;
        },

        scrambleWord(word) {
            return word.split('')
                .sort(() => Math.random() - 0.5)
                .join('');
        },

        newWord() {
            const words = this.getCurrentCategoryWords();
            const randomIndex = Math.floor(Math.random() * words.length);
            this.currentWord = words[randomIndex].word;
            this.currentHint = words[randomIndex].hint;
            this.scrambledWord = this.scrambleWord(this.currentWord);
            this.userGuess = '';
            this.message = '';
        },

        checkGuess() {
            if (this.userGuess.toUpperCase() === this.currentWord) {
                this.score += 10;
                this.message = 'Correct! +10 points';
                this.isCorrect = true;
                this.playSound('correctSound');
                setTimeout(() => this.newWord(), 1500);
            } else {
                this.message = 'Try again!';
                this.isCorrect = false;
                this.playSound('wrongSound');
            }
        },

        playSound(soundId) {
            if (this.soundEnabled) {
                const sound = document.getElementById(soundId);
                sound.currentTime = 0;
                sound.play();
            }
        },

        toggleSound() {
            this.soundEnabled = !this.soundEnabled;
        }
    }
}
</script>

<style>
.aspect-square {
    aspect-ratio: 1 / 1;
    width: 100%;
}
</style> 