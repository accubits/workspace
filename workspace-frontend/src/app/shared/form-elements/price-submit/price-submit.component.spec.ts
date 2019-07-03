import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PriceSubmitComponent } from './price-submit.component';

describe('PriceSubmitComponent', () => {
  let component: PriceSubmitComponent;
  let fixture: ComponentFixture<PriceSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PriceSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PriceSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
