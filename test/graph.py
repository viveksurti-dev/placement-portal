import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns

titanic = sns.load_dataset('titanic')
sns.set(style="whitegrid")

# 1. Create BAR plot showing number of passenger by PCLASS Using Matplotlib,Using Seaborn library  

pclass_counts = titanic['pclass'].value_counts().sort_index()

# matplotlib
plt.figure(figsize=(8,5))
plt.bar(pclass_counts.index, pclass_counts.values, 
        color=['#3498db', '#2ecc71', '#e74c3c'], 
        edgecolor='black')
plt.title('Number of Passengers by PCLASS')
plt.xlabel('Passenger Class')
plt.ylabel('Number of Passengers')
plt.xticks([1, 2, 3])
plt.grid(axis='y', alpha=0.7)
plt.show()

# Seaborn
plt.figure(figsize=(8,5))
sns.countplot(x='pclass', data=titanic, 
              palette=['#3498db', '#2ecc71', '#e74c3c'], 
              edgecolor='black')
plt.title('Number of Passengers by PCLASS (Seaborn)')
plt.xlabel('Passenger Class')
plt.ylabel('Count')
plt.show()


# 2. Create LINE plot showing passenger fare by passenger class wise Using Matplotlib, Using Seaborn library 

fare_means = titanic.groupby('pclass')['fare'].mean().sort_index()

# matplotlib
plt.figure(figsize=(8,5))
plt.plot(fare_means.index, fare_means.values, 
         marker='o', linestyle='-', color='#9b59b6')  # Purple
plt.title('Average Fare by Passenger Class')
plt.xlabel('Passenger Class')
plt.ylabel('Average Fare')
plt.xticks([1,2,3])
plt.grid(True, linestyle='--', alpha=0.7)
plt.show()

# Seaborn
plt.figure(figsize=(8,5))
sns.lineplot(x='pclass', y='fare', data=titanic, 
             estimator='mean', marker='o', color='#9b59b6')
plt.title('Average Fare by Passenger Class (Seaborn)')
plt.xlabel('Passenger Class')
plt.ylabel('Average Fare')
plt.show()

# 3. Create SCATTER plot showing passenger Age and survival relationship Using, Matplotlib, Using Seaborn library 

colors = titanic['survived'].map({0: '#e74c3c', 1: '#2ecc71'})

# Matplotlib 
plt.figure(figsize=(8,5))
plt.scatter(titanic['age'], titanic['survived'], 
            c=colors, alpha=0.6, edgecolors='w', s=60)
plt.title('Passenger Age and Survival Relationship')
plt.xlabel('Age')
plt.ylabel('Survived (0 = No, 1 = Yes)')
plt.grid(True, linestyle='--', alpha=0.7)
plt.show()

# Seaborn
plt.figure(figsize=(8,5))
sns.scatterplot(x='age', y='survived', data=titanic, 
                hue='survived', palette={0: '#e74c3c', 1: '#2ecc71'}, alpha=0.6)
plt.title('Passenger Age and Survival Relationship (Seaborn)')
plt.xlabel('Age')
plt.ylabel('Survived')
plt.show()


# 4. Create PIE plot showing number of passenger survive Using Matplotlib only.  
survival_counts = titanic['survived'].value_counts()

# Matplotlib pie chart
plt.figure(figsize=(6,6))
plt.pie(survival_counts, 
        labels=['Did Not Survive', 'Survived'], 
        autopct='%1.1f%%', startangle=90, 
        colors=['#c0392b', '#27ae60'],
        explode=[0.1, 0])
plt.title('Passenger Survival Distribution')
plt.show()

# 5. Create HISTOGRAM of passenger ages Using Matplotlib, Using Seaborn library  
# Matplotlib
plt.figure(figsize=(8,5))
plt.hist(titanic['age'].dropna(), bins=30, 
         color='#f39c12', edgecolor='black')
plt.title('Distribution of Passenger Ages')
plt.xlabel('Age')
plt.ylabel('Number of Passengers')
plt.grid(axis='y', linestyle='--', alpha=0.7)
plt.show()

# Seaborn
plt.figure(figsize=(8,5))
sns.histplot(titanic['age'], bins=30, kde=True, 
             color='#8e44ad')  
plt.title('Distribution of Passenger Ages (Seaborn)')
plt.xlabel('Age')
plt.ylabel('Count')
plt.show()
