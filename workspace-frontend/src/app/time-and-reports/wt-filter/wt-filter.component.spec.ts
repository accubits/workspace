import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WtFilterComponent } from './wt-filter.component';

describe('WtFilterComponent', () => {
  let component: WtFilterComponent;
  let fixture: ComponentFixture<WtFilterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WtFilterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WtFilterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
