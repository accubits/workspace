import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TrainingRequestComponent } from './training-request.component';

describe('TrainingRequestComponent', () => {
  let component: TrainingRequestComponent;
  let fixture: ComponentFixture<TrainingRequestComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TrainingRequestComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TrainingRequestComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
