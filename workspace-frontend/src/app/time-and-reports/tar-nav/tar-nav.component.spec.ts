import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TarNavComponent } from './tar-nav.component';

describe('TarNavComponent', () => {
  let component: TarNavComponent;
  let fixture: ComponentFixture<TarNavComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TarNavComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TarNavComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
