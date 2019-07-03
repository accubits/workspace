import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskCarouselWidgetComponent } from './task-carousel-widget.component';

describe('TaskCarouselWidgetComponent', () => {
  let component: TaskCarouselWidgetComponent;
  let fixture: ComponentFixture<TaskCarouselWidgetComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskCarouselWidgetComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskCarouselWidgetComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
