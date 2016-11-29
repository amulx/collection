<?php
namespace Xiucai\ServiceBundle\Command;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Xiucai\PageBundle\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Predis;

class sendCommand extends ContainerAwareCommand
{
    protected $container;
    protected $doctrine;

    protected function configure(){
        $this
            ->setName('xiucai:sendemail')
            ->setDescription('send email command')
            //->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
            //->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $output->writeln($this->send());
    }

    public function send(){
        try{
            $em = $this->getContainer()->get('doctrine');
            $redis = $this->getContainer()->get('snc_redis.default');
            $date1 = date('Y-m-d H:i',strtotime(date('Y-m-d H:i:s'))+1800).':00';
            $repository = $em ->getRepository('StoreBundle:XcLiveCourse');
            $query = $repository->createQueryBuilder('c')
                ->where("c.status=4 and c.scheduleTime=:time")
                -> setParameter('time',$date1)
                ->getQuery();

            $data_live_course = $query->getResult();
            foreach($data_live_course as $value){
                $key = "live_course_reserve:".$value['id'];
                //$key = "live_course_reserve:1";
                $data = $redis->zrange($key,0,-1);
                foreach($data as $v){
                    $dataObj = json_decode($v);
                    $email = $dataObj->email;
                    echo $email;
                    if($email != ""){
                        $message = \Swift_Message::newInstance()
                            ->setSubject('秀财网直播课堂')
                            ->setFrom('no-reply@xiucai.com')
                            ->setTo($email,$email)
                            //->attach(\Swift_Attachment::fromPath(__DIR__.'/Email/zhibo-norm.pdf'))
                            ->setBody("您(预约)的课程将在30分钟后正式开始，请尽快进入直播间!本次直播开始时间：".date("Y-m-d H:i",time()+1800)."【秀财网】")
                            //->setBody($this->renderView('PageBundle:Billing:email.html.twig'))
                        ;
                        $this->getContainer()->get('mailer')->send($message);
                    }
                }
            }
            return '-----------'.date("Y-m-d H:i:m").'----ok----------------------------------------';
        }catch (\Symfony\Component\Config\Definition\Exception\Exception $e){
            $this->logger($e);
//            $e -> getMessage();
        }
    }

    public function sendtest(){
        try{
            $message = \Swift_Message::newInstance()
                ->setSubject('秀财网直播课堂')
                ->setFrom('no-reply@xiucai.com')
                ->setTo("tony@xiucai.com","tony@xiucai.com")
                //->attach(\Swift_Attachment::fromPath(__DIR__.'/Email/zhibo-norm.pdf'))
                ->setBody("您(预约)的课程将在30分钟后正式开始，请尽快进入直播间!本次直播开始时间：".date("Y-m-d H:i",time()+1800)."【秀财网】")
                //->setBody($this->renderView('PageBundle:Billing:email.html.twig'))
            ;
            $this->getContainer()->get('mailer')->send($message);
        }catch (\Symfony\Component\Config\Definition\Exception\Exception $e){
            $this->logger($e);
        }
    }

    public function logger($e){
        $logger = $this->getContainer()->get('logger');
        $logger->err("Justin sendemail : ".$e);
    }
}
?>