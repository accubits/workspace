import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskSentbymeComponent } from './task-sentbyme.component';

describe('TaskSentbymeComponent', () => {
  let component: TaskSentbymeComponent;
  let fixture: ComponentFixture<TaskSentbymeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskSentbymeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskSentbymeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
