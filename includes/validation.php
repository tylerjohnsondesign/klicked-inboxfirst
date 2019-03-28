<?php
/**
Set Namespace
**/
namespace EmailValidator;

/**
Email Validator
**/
class Validator {
    // Disposable email domains.
    private $disposable = null;
    
    // Role email addresses.
    private $role = null;
    
    // Example top level domains.
    private $exampleTlds = [
        '.test',
        '.example',
        '.invalid',
        '.localhost',
    ];
    
    // Example domains.
    private $exampleDomains = [
        'example.com',
        'example.net',
        'example.org',
    ];
    
    // Validate email address using ALL functions.
    public function isValid($email) {
        // Is it an email address?
        if(!$this->isEmail($email)) {
            return false;
        }
        
        // Is it an example domain?
        if($this->isExample($email)) {
            return false;
        }
        
        // Is it a disposable email address?
        if($this->isDisposable($email)) {
            return false;
        }
        
        // Is it a role based email address?
        if($this->isRole($email)) {
            return false;
        }
        
        // Does the domain have MX record(s)?
        if(!$this->hasMX($email)) {
            return false;
        }
        
        return true;
    }
    
    // Check if email address.
    public function isEmail($email) {
        if(is_string($email)) {
            return (bool) preg_match('/^.+@.+\..+$/i', $email);
        }
        
        return false;
    }
    
    // Check for example email addresses.
    public function isExample($email) {
        // Check for email address.
        if(!$this->isEmail($email)) {
            return null;
        }
        
        // Get the hostname
        $hostname = $this->hostnameFromEmail($email);
        
        // Check for a hostname and keep going.
        if($hostname) {
            // Check against example domains
            if(in_array($hostname, $this->exampleDomains)) {
                return true;
            }
            
            // Check top level domains
            foreach($this->exampleTlds as $tld) {
                $length = strlen($tld);
                $subStr = substr($hostname, -$length);
                
                if($subStr == $tld) {
                    return true;
                }
            }
            
            return false;
        }
        
        return null;
    }
    
    // Detect disposable email address.
    public function isDisposable($email) {
        // Check if email address.
        if(!$this->isEmail($email)) {
            return null;
        }
        
        // Get the hostname.
        $hostname = $this->hostnameFromEmail($email);
        
        // If there's a hostname, keep going.
        if($hostname) {
            // Load disposable email domains
            if(is_null($this->disposable)) {
                $this->disposable = include(IFKLICKED_BASE_PATH.'includes/email-data/disposable.php');
            }
            
            // Search array for hostname.
            if(in_array($hostname, $this->disposable)) {
                return true;
            }
            
            return false;
        }
        
        return null;
    }
    
    // Detect role based email addresses.
    public function isRole($email) {
        // Check if email address.
        if(!$this->isEmail($email)) {
            return null;
        }
        
        // Get email user.
        $user = $this->userFromEmail($email);
        
        // If we have a user, continue on.
        if($user) {
            // Load roles.
            if(is_null($this->role)) {
                $this->role = include(IFKLICKED_BASE_PATH.'includes/email-data/roles.php');
            }
            
            // Search array for roles.
            if(in_array($user, $this->role)) {
                return true;
            }
            
            return false;
        }
        
        return null;
    }
    
    // Check for MX record(s).
    public function hasMX($email) {
        // Check if email address.
        if(!$this->isEmail($email)) {
            return null;
        }
        
        // Get the hostname.
        $hostname = $this->hostnameFromEmail($email);
        
        // If we have a hostname, let's keep going.
        if($hostname) {
            return checkdnsrr($hostname, 'MX');
        }
        
        return null;
    }
    
    // Get the user from an email address.
    private function userFromEmail($email) {
        // Explode the email address.
        $parts = explode('@', $email);
        
        // If it has two parts, return the user.
        if(count($parts) == 2) {
            return strtolower($parts[0]);
        }
        
        return null;
    }
    
    // Get the hostname from an email address.
    private function hostnameFromEmail($email) {
        // Explode the email address.
        $parts = explode('@', $email);
        
        // If it has two parts, return the hostname.
        if(count($parts) == 2) {
            return strtolower($parts[1]);
        }
        
        return null;
    }
}