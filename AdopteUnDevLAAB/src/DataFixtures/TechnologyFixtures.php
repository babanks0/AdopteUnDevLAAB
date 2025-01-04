<?php

namespace App\DataFixtures;

use App\Entity\Technology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TechnologyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            // Langages de programmation
            "PHP", "Java", "Python", "JavaScript", "Ruby", "C", "C++", "C#", "Go", "Rust", 
            "Kotlin", "Swift", "Dart", "TypeScript", "R", "Scala", "Perl", "MATLAB", 
            "Lua", "Objective-C",
        
            // Frameworks web et backend
            "Laravel", "Symfony", "CodeIgniter", "Django", "Flask", "Express.js", 
            "Spring Boot", "Ruby on Rails", "ASP.NET", "NestJS", "FastAPI", 
        
            // Frameworks front-end
            "React", "Vue.js", "Angular", "Svelte", "Ember.js", "Backbone.js",
        
            // Frameworks mobiles
            "Flutter", "React Native", "Ionic", "Xamarin", "SwiftUI", "Jetpack Compose",
        
            // Bases de données
            "MySQL", "PostgreSQL", "SQLite", "MongoDB", "Redis", "Cassandra", "CouchDB",
            "MariaDB", "Elasticsearch", "Firebase Firestore", "DynamoDB",
        
            // Outils DevOps et CI/CD
            "Docker", "Kubernetes", "Jenkins", "GitLab CI/CD", "CircleCI", "Travis CI",
            "Ansible", "Terraform", "Vagrant", "Nagios", "Prometheus", "Grafana",
        
            // Cloud Providers
            "AWS", "Azure", "Google Cloud", "IBM Cloud", "Oracle Cloud", "DigitalOcean",
            "Linode", "Heroku", "Vercel", "Netlify",
        
            // Outils de gestion de projets
            "Jira", "Trello", "Asana", "Monday.com", "ClickUp", "Notion",
        
            // Langages de script
            "Shell", "Bash", "PowerShell", "Groovy",
        
            // Technologies de données et d'IA
            "TensorFlow", "PyTorch", "Keras", "Pandas", "NumPy", "SciPy", "Matplotlib", 
            "Scikit-learn", "NLTK", "OpenCV", "Hadoop", "Spark",
        
            // Frameworks de jeux
            "Unity", "Unreal Engine", "Godot", "CryEngine", "Cocos2d",
        
            // Technologies de cybersécurité
            "Metasploit", "Wireshark", "Snort", "Kali Linux", "Burp Suite", "Nmap",
        
            // Outils de gestion de version
            "Git", "SVN", "Mercurial",
        
            // Outils pour l'IoT
            "Arduino", "Raspberry Pi", "MQTT", "Zigbee",
        
            // Divers
            "Bootstrap", "Tailwind CSS", "Sass", "Less", "Webpack", "Rollup", "Parcel", 
            "Three.js", "D3.js", "Electron", "Cordova", "Capacitor"
        ];
        
        foreach($data as $item){
            $technology = new Technology();
            $technology->setTitre($item);
            $manager->persist($technology);
        }
        $manager->flush();
    }
}
