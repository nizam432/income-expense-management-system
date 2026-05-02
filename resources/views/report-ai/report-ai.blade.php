<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>AI Report Analyzer</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body style="font-family: sans-serif; padding: 40px; background: #f8fafc;">
    <h2>📊 AI Report Analyzer</h2>
    <p>রিপোর্ট নিয়ে যেকোনো প্রশ্ন করুন (বাংলা বা ইংরেজি):</p>

    <input type="text" id="question" placeholder="উদাহরণ: এই মাসে মোট বিক্রি কত?" 
           style="width: 400px; padding: 8px;">
    <button id="askBtn" style="padding: 8px 16px;">Ask AI</button>

    <div id="answer" style="margin-top: 20px; padding: 10px; background: #eef; border-radius: 8px;"></div>

    <script>
        document.getElementById('askBtn').addEventListener('click', async () => {
            const question = document.getElementById('question').value;
            document.getElementById('answer').innerText = '⏳ AI বিশ্লেষণ করছে...';

            try {
                const res = await axios.post('{{ route('report.ai') }}', { question });
                document.getElementById('answer').innerText = res.data.answer;
            } catch (err) {
                document.getElementById('answer').innerText = '❌ ত্রুটি ঘটেছে। আবার চেষ্টা করুন।';
            }
        });
    </script>
</body>
</html>
