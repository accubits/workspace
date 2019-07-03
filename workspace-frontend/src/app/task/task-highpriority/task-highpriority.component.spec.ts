import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskHighpriorityComponent } from './task-highpriority.component';

describe('TaskHighpriorityComponent', () => {
  let component: TaskHighpriorityComponent;
  let fixture: ComponentFixture<TaskHighpriorityComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskHighpriorityComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskHighpriorityComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
