<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $posts = [
            [
                'title' => 'أساسيات تطوير الويب',
                'body'  => 'تطوير الويب يبدأ بتعلم HTML و CSS لإنشاء الصفحات، ثم JavaScript لإضافة التفاعل. يمكن استخدام لغات مثل PHP أو Python لإنشاء الخوادم.',
                'image' => "https://en.idei.club/uploads/posts/2023-06/thumbs/1686321251_en-idei-club-p-programing-language-dizain-58.jpg"
            ],
            [
                'title' => 'Frontend vs Backend',
                'body'  => 'الفرق بين الـ Frontend والـ Backend في تطوير الويب مهم. الـ Frontend مسؤول عن تصميم الواجهة، والـ Backend مسؤول عن معالجة البيانات وتخزينها.',
                'image' => "https://5.imimg.com/data5/WQ/PI/GLADMIN-53522917/software-programing-courses.jpg"
            ],
            [
                'title' => 'أفضل لغات الويب',
                'body'  => 'JavaScript، PHP، وPython هي من أقوى اللغات المستخدمة في تطوير الويب. كل لغة لها استخداماتها وفقًا لنوع المشروع ومتطلباته.',
                'image' => "https://i0.wp.com/plmstack.com/wp-content/uploads/2018/09/business-code-coding-270360.jpg?fit=1680%2C1119&ssl=1"
            ],
            [
                'title' => 'أهمية تحسين SEO',
                'body'  => 'تحسين محركات البحث (SEO) يساعد في رفع ترتيب الموقع على Google، مما يزيد من عدد الزوار والمستخدمين المستهدفين بشكل كبير.',
                'image' => "https://cdn2.vectorstock.com/i/1000x1000/81/16/computer-with-code-programing-software-data-vector-17618116.jpg"
            ],
            [
                'title' => 'الفرق بين CSS و SCSS',
                'body'  => 'SCSS هو نسخة محسنة من CSS تقدم ميزات مثل المتغيرات والتداخل، مما يسهل كتابة أنماط منظمة وقابلة لإعادة الاستخدام.',
                'image' => "https://static.vecteezy.com/system/resources/previews/025/910/351/non_2x/computer-programing-coding-web-development-with-isometric-laptop-displaying-futuristic-ui-vector.jpg"
            ],
            [
                'title' => 'Responsive Web Design',
                'body'  => 'التصميم المتجاوب يسمح بعرض الموقع بشكل جيد على جميع الأجهزة، مما يحسن تجربة المستخدم ويساهم في تحسين الـ SEO.',
                'image' => "https://miro.medium.com/v2/resize:fit:12000/0*l1Wxf8h2AkXqYiEf"
            ],
            [
                'title' => 'أمان المواقع الإلكترونية',
                'body'  => 'حماية المواقع من الهجمات ضرورية. استخدم HTTPS، تحقق من المدخلات، واحرص على تحديث البرمجيات للحماية من الثغرات.',
                'image' => "https://thumbs.dreamstime.com/z/programming-code-abstract-background-screen-software-programing-workflow-algorithm-concept-python-developer-php-development-259578160.jpg"
            ],
            [
                'title' => 'What is a JavaScript Framework?',
                'body'  => 'A JavaScript framework like React, Angular, or Vue helps developers build dynamic user interfaces efficiently.',
                'image' => "https://www.lightsregionalinnovation.com/wp-content/uploads/2021/03/704318ee9be94acabf28919a734951b8-scaled.jpg"
            ],
            [
                'title' => 'تطوير واجهات ديناميكية',
                'body'  => 'يمكن استخدام JavaScript و AJAX لإنشاء مواقع ديناميكية تتفاعل مع المستخدم دون الحاجة إلى إعادة تحميل الصفحة بالكامل.',
                'image' => "https://i0.wp.com/garonpower.com/wp-content/uploads/2019/01/computer-programming.jpeg?fit=1500%2C1000&ssl=1"
            ],
            [
                'title' => 'كيف تبدأ كمطور ويب؟',
                'body'  => 'ابدأ بتعلم HTML و CSS، ثم JavaScript. بعد ذلك، اختر إطار عمل Frontend أو Backend لبناء مشاريع احترافية.',
                'image' => "https://images.squarespace-cdn.com/content/v1/54641059e4b0481ef69021ad/1621447930417-4YWGUOSCBKONM46ITYKR/virtual+programing+AdobeStock_327586852+%5BConverted%5D.jpg?format=1000w"
            ]
        ];




        // $images = [
        //     "storage/postsImages/1.png",
        //     "storage/postsImages/2.jpg",
        //     "storage/postsImages/3.jpg",
        //     "storage/postsImages/4.jpg",
        //     "storage/postsImages/5.jpg",
        //     "storage/postsImages/6.jpg",
        //     "storage/postsImages/7.jpg",
        //     "storage/postsImages/8.jpg",
        //     "storage/postsImages/9.jpg",
        //     "storage/postsImages/10.png",
        // ];

        foreach (range(0, 9) as $i) {
            Post::create([
                'title' => $posts[$i]['title'],
                'body' => $posts[$i]['body'],
                'image' => $posts[$i]['image'],
                'category_id' => rand(1, 8), // تأكد من أن لديك فئات مسجلة
                'user_id' => rand(1, 5), // تأكد من أن لديك مستخدمين مسجلين
            ]);
        }
    }
}
/*





"https://en.idei.club/uploads/posts/2023-06/thumbs/1686321251_en-idei-club-p-programing-language-dizain-58.jpg"
"https://5.imimg.com/data5/WQ/PI/GLADMIN-53522917/software-programing-courses.jpg"
"https://i0.wp.com/plmstack.com/wp-content/uploads/2018/09/business-code-coding-270360.jpg?fit=1680%2C1119&ssl=1"
"https://cdn2.vectorstock.com/i/1000x1000/81/16/computer-with-code-programing-software-data-vector-17618116.jpg"
"https://static.vecteezy.com/system/resources/previews/025/910/351/non_2x/computer-programing-coding-web-development-with-isometric-laptop-displaying-futuristic-ui-vector.jpg"
"https://miro.medium.com/v2/resize:fit:12000/0*l1Wxf8h2AkXqYiEf"
"https://thumbs.dreamstime.com/z/programming-code-abstract-background-screen-software-programing-workflow-algorithm-concept-python-developer-php-development-259578160.jpg"
"https://www.lightsregionalinnovation.com/wp-content/uploads/2021/03/704318ee9be94acabf28919a734951b8-scaled.jpg"
"https://i0.wp.com/garonpower.com/wp-content/uploads/2019/01/computer-programming.jpeg?fit=1500%2C1000&ssl=1"
"https://images.squarespace-cdn.com/content/v1/54641059e4b0481ef69021ad/1621447930417-4YWGUOSCBKONM46ITYKR/virtual+programing+AdobeStock_327586852+%5BConverted%5D.jpg?format=1000w"

*/
