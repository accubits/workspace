import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskCompletedComponent } from './task-completed.component';

describe('TaskCompletedComponent', () => {
  let component: TaskCompletedComponent;
  let fixture: ComponentFixture<TaskCompletedComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskCompletedComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskCompletedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
