<?php

namespace Theme\AssetManager;

/**
 *
 * @author PedromDev
 */
abstract class AbstractAssetDependency extends AbstractAsset implements
    DependencyInterface
{
    /**
     *
     * @var DependencyInterface[]
     */
    private $dependencies = [];
    
    /**
     * {@inheritDoc}
     */
    public function add(DependencyInterface $dependency)
    {
        $this->checkDependency($dependency);
        $this->dependencies[$dependency->getName()] = $dependency;
    }

    /**
     * {@inheritDoc}
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw DependencyException::dependencyNotFound($name);
        }
        
        return $this->dependencies[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        return $this->dependencies;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->getIdentifier();
    }

    /**
     * {@inheritDoc}
     */
    public function has($name)
    {
        if (!$this->hasDependencies()) {
            return false;
        } elseif (isset($this->dependencies[$name])) {
            return true;
        } else {
            foreach ($this->getAll() as $dependency) {
                if ($dependency->has($name)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function hasDependencies()
    {
        return !empty($this->dependencies);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($name)
    {
        if (!$this->has($name)) {
            return false;
        }
        $dependencies = $this->dependencies;
        unset($dependencies[$name]);
        $this->dependencies = $dependencies;
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function removeAll()
    {
        $names = array_keys($this->dependencies);
        $removed = true;
        foreach ($names as $name) {
            $removed = $removed && $this->remove($name);
        }
        return $removed;
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {
        $this->setIdentifier($name);
    }
    
    /**
     * {@inheritDoc}
     */
    public function enqueue()
    {
        if ($this->hasDependencies()) {
            /* @var $dependency AbstractAssetDependency */
            foreach ($this->getAll() as $dependency) {
                $dependency->enqueue();
            }
        }
        $this->setStatus(Asset\QueueStatus::ENQUEUED);
    }
    
    private function checkDependency(DependencyInterface $dependency)
    {
        if ($this->has($dependency->getName())) {
            throw DependencyException::dependencyAlreadyExists(
                $dependency->getName(),
                $this->getName()
            );
        } elseif ($dependency->has($this->getName())) {
            throw DependencyException::circularDependency(
                $dependency->getName(),
                $this->getName()
            );
        } elseif ($dependency->getName() == $this->getName()) {
            throw DependencyException::autoDependency($dependency->getName());
        } elseif (!($dependency instanceof AbstractAssetDependency)) {
            throw DependencyException::invalidDependencyType(
                __CLASS__,
                $dependency
            );
        }
    }
}
