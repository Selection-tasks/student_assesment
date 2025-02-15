document.addEventListener("DOMContentLoaded", function () {
    const quizContainer = document.getElementById("quiz");
    const submitButton = document.getElementById("submitQuiz");

    // Sample questions (could be loaded from a database in a real project)
    const questions = [
        {
            question: "What is the output of 2 + 2 in JavaScript?",
            options: ["3", "4", "5", "22"],
            answer: "4"
        },
        {
            question: "Which HTML tag is used to define a JavaScript file?",
            options: ["<script>", "<js>", "<javascript>", "<code>"],
            answer: "<script>"
        }
    ];

    // Function to load quiz questions dynamically
    function loadQuiz() {
        quizContainer.innerHTML = "";
        questions.forEach((q, index) => {
            const questionDiv = document.createElement("div");
            questionDiv.classList.add("question");
            questionDiv.innerHTML = `<p>${index + 1}. ${q.question}</p>`;

            const optionsDiv = document.createElement("div");
            optionsDiv.classList.add("options");

            q.options.forEach(option => {
                const label = document.createElement("label");
                label.innerHTML = `<input type="radio" name="question${index}" value="${option}"> ${option}`;
                optionsDiv.appendChild(label);
            });

            questionDiv.appendChild(optionsDiv);
            quizContainer.appendChild(questionDiv);
        });
    }

    // Submit quiz answers
    submitButton.addEventListener("click", function () {
        let score = 0;
        let answers = {};

        questions.forEach((q, index) => {
            const selectedOption = document.querySelector(`input[name="question${index}"]:checked`);
            if (selectedOption) {
                answers[`question${index + 1}`] = selectedOption.value;
                if (selectedOption.value === q.answer) {
                    score++;
                }
            } else {
                answers[`question${index + 1}`] = "Not answered";
            }
        });

        // Prepare the data to send
        const quizData = {
            score: score,
            answers: answers
        };

        // Send data to PHP using fetch()
        fetch("quiz.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(quizData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Quiz submitted successfully! Your score: ${score}/${questions.length}`);
            } else {
                alert(`Error: ${data.error}`);
            }
        })
        .catch(error => {
            console.error("Error submitting quiz:", error);
            alert("An error occurred while submitting the quiz.");
        });
    });

    // Load quiz questions when page loads
    loadQuiz();
});
