import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RatingSubmitComponent } from './rating-submit.component';

describe('RatingSubmitComponent', () => {
  let component: RatingSubmitComponent;
  let fixture: ComponentFixture<RatingSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RatingSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RatingSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
