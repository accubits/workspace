import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RatingResponseComponent } from './rating-response.component';

describe('RatingResponseComponent', () => {
  let component: RatingResponseComponent;
  let fixture: ComponentFixture<RatingResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RatingResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RatingResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
